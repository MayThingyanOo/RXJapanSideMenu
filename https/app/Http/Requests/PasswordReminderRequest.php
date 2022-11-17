<?php

namespace App\Http\Requests;

use App\Models\Staff;
use App\Models\StaffPasswordReminder;
use Illuminate\Foundation\Http\FormRequest;

class PasswordReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $errors = $validator->errors();

            // hash,is_used,password_character_used,is_within_ten_min validation error
            if (isset($errors->toArray()['h'][0])) {
                Abort(404);
            }

            if ($errors->isEmpty() && $this->method() == 'POST') {
                $staff_id = StaffPasswordReminder::where('hash', request()->hash)->pluck('staff_id')->first();
                $staff_email = Staff::where('staff_id', $staff_id)->pluck('email')->first();
                $explode_staff_email = explode('@', $staff_email);
                if ($this->request->get('password') == $staff_email || $this->request->get('password') == $explode_staff_email[0]) {
                    $errors->add("password", cps_trans('validation.email_password_same'));
                }
            }
        });
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                $rules = [
                    'h' => 'required|max:255|is_used|exists:qpass.staff_password_reminder,hash|is_within_ten_min'
                ];

                return $rules;
            case 'POST':
                $rules = [
                    'password' => 'required|max:255|min:8|password_character_used|password_rules|confirmed',
                    'hash' => 'required|max:255|is_used|exists:qpass.staff_password_reminder,hash|is_within_ten_min'
                ];

                return $rules;
            default:
                return [];
        }
    }
}
