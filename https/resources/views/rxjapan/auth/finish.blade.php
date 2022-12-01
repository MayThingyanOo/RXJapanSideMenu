@extends('rxjapan.layouts.auth')
@section('title', 'パスワード再発行メールの送信完了')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div class="qb-login-box">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">パスワード再設定メール送信完了​</p>
            <p class="font-normal text-center mb00">
                ご入力いただいたメールアドレス宛に<br>
                パスワード再設定用のURLを送信しました。<br>
                メールに記載された手順に従ってパスワードを<br>
                再設定してください。
            </p>
        </div>
        <div class="text-center">
            <a href="{{ route('get_login') }}" class="login-back">ログイン画面に戻る</a>
        </div>
    </div>
@endsection
