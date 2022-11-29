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

    'before' => ':attributeは:date以前の日付にしてください。',
    'between' => ['numeric' => ':attributeは:min〜:maxまでにしてください。',
        'file' => ':attributeは:min〜:max KBまでのファイルにしてください。',
        'string' => ':attributeは:min〜:max文字にしてください。',
        'array' => ':attributeは:min〜:max個までにしてください。'],
    'confirmed' => '新規パスワードとパスワード再確認が一致しません。',
    'email' => '正しいメールアドレスを入力してください。',
    'email.not_registered' => '入力したメールアドレスが登録されていません。',
    'email_distinct' => '同じメールアドレスが入っています。',
    'end_time_after_start_time' => '終了時間は、開始時間以降にしてください。',
    'exists' => '選択された:attributeは正しくありません。',
    'image' => ':attributeは画像にしてください。',
    'integer' => ':attributeは半角数字で入力してください。',
    'ip' => ':attributeを正しいIPアドレスにしてください。',
    'max' => ['numeric' => ':attributeは:max以下にしてください。',
        'file' => ':attributeは:max KB以下のファイルにしてください。.',
        'string' => ':attributeは:max文字以下にしてください。',
        'array' => ':attributeは:max個以下にしてください。'],
    'mimes' => ':attributeは:valuesタイプのファイルにしてください。',
    'min' => ['numeric' => ':min以上で入力してください。',
        'file' => ':min KB以上のファイルにしてください。.',
        'string' => ':min文字以上で入力してください。',
        'array' => ':min個以上にしてください。'],
    'required' => '入力してください。',
    'size' => ['numeric' => ':attributeは:size桁で入力してください。',
        'file' => ':attributeは:size KBにしてください。.',
        'string' => ':attributeは:size桁で入力してください。',
        'array' => ':attributeは:size個にしてください。'],
    'string' => ':attributeは文字列にしてください。',
    'unique' => '既に登録されている:attributeです。',
    'password_character_used' => '半角英数字、半角記号で入力してください。',
    'password_rules' => '半角の英字、数字、記号をそれぞれ1文字以上使用してください。',
    'password_simple_rules' => '半角の英字、数字をそれぞれ1文字以上使用してください。',
    'password_reset.incorrect_access_url' => 'アクセスしたURLが正しくありません。',
    'password_reset.complete' => 'パスワードの再設定は完了しています。',
    'password_reset.url_expired' => 'アクセスしたURLは有効期限が切れています。',
    'password_reset.link_expired' => 'リンクの有効期限が切れました。再度パスワード再設定の手続きをしてください。',
    'password_reset.is_used_visitor' => 'パスワードは再設定済みです。',
    'password_reset.invalid_input' => '入力値が不正です。',

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
