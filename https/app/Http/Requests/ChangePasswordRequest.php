<?php

namespace App\Http\Requests;

use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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

            $current_pwd = $validator->getData()['old_password'];
            $new_password = $validator->getData()['new_password'];
            $staff = \Session::get('staff');
            $staff_email = $staff->email;
            $explode_staff_email = explode('@', $staff_email);

            if (!Hash::check($current_pwd, $staff->password)) {
                $errors->add('old_password', cps_trans('validation.old_password'));
            }
            if (Hash::check($new_password, $staff->password)) {
                $errors->add('new_password', cps_trans('validation.new_password'));
            }
            if ($new_password == $staff_email || $new_password == $explode_staff_email[0]) {
                $errors->add('new_password', cps_trans('validation.not_same_email_address'));
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|max:255|min:8|password_character_used|password_rules',
            'confirm_password' => 'required|max:255|min:8|password_character_used|
                                    password_rules|same:new_password',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'old_password' => '現在のパスワード',
            'new_password' => '新規パスワード',
            'confirm_password' => 'パスワード確認',
        ];
    }
}
