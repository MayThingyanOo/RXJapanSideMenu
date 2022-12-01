<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePasswordRequest extends FormRequest
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

    public function rules()
    {
        return ['password' => 'required|min:8|max:255|password_character_used|password_rules|confirmed'];
    }

    public function messages()
    {
        return [
            'password.min' => ':min文字以上で入力してください。',
            'password.max' => ':max文字以下で入力してください。',
        ];
    }

    public function attributes()
    {
        return ['password' => 'パスワード'];
    }
}
