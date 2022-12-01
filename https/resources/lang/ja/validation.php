<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */
    'confirmed' => '新規パスワードとパスワード再確認が一致しません。',
    'email' => '正しいメールアドレスを入力してください。',
    'exists' => '選択された:attributeは正しくありません。',
    'integer' => ':attributeは半角数字で入力してください。',
    'max' => ['numeric' => ':attributeは:max以下にしてください。',
        'string' => ':attributeは:max文字以下にしてください。'],
    'min' => ['numeric' => ':min以上で入力してください。',
        'string' => ':min文字以上で入力してください。',
    ],
    'required' => '入力してください。', //
    'same' => ':attributeと:otherは一致していません。',
    'email_password_same' => 'メールアドレスと同じ文字列では設定できません。',
    'password_character_used' => '半角英数字、半角記号で入力してください。',
    'password_rules' => '半角の英字、数字、記号をそれぞれ1文字以上使用してください。',
    'password_simple_rules' => '半角の英字、数字をそれぞれ1文字以上使用してください。',
    'old_password' => '現在のパスワードが正しくありません。',
    'new_password' => '現在のパスワードと同じ文字列では設定できません。',
    'not_same_email_address' => 'メールアドレスと同じ文字列では設定できません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],
];
