@extends('rxjapan.layouts.auth')
@section('title', 'パスワード再設定の完了')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div class="qb-login-box">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">パスワード再設定完了</p>
            <p class="font-normal text-center mb00">パスワードの再設定が完了しました。<br> 下記よりサービスにログインしてください。</p>
        </div>
        <div class="text-center">
            <a href="{{ route('get_login') }}" class="login-back">ログイン画面に戻る</a>
        </div>
    </div>
@endsection
