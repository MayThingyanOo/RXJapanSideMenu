@extends('rxjapan.layouts.auth')
@section('title', 'ログイン')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div class="qb-login-box {{ $errors->has('email') || $errors->has('password') ? '' : 'animation' }}">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">ログイン</p>
            <p class="font-normal">登録したメールアドレスとパスワードを入力してください。</p>
        </div>
        <div class="login-box-body qb-login-box-body">
            @if ($errors->has('email') || $errors->has('password'))
                <div class="alert alert-danger mb30" id="click_hide">
                    メールアドレス・パスワードを確認してください。
                </div>
            @endif

            <form method="post" action="{{ route('action_login') }}" novalidate>
                {!! csrf_field() !!}

                <div class="box-container box-space-between mb30">
                    <div class="box-10 box-container box-bottom box-center align-center">
                        <i class="fa fa-solid fa-envelope md-gray"></i>
                    </div>
                    <div class="box-90 login-screen">
                        <div class="form-group label-floating {{ old('email') ? 'has-error' : '' }} pb00 mt00">
                            <label class="control-label">メールアドレス</label>
                            <input type="email" class="form-control pl00 login-email" name="email"
                                value="{{ old('email') }}" id="testest">
                            <span class="material-input"></span>
                        </div>
                    </div>
                </div>

                <div class="box-container box-space-between mb30">
                    <div class="box-10 box-container box-bottom box-center align-center">
                        <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                    </div>
                    <div class="box-90 login-screen">
                        <div class="form-group label-floating pb00 mt00">
                            <label class="control-label">パスワード</label>
                            <input type="password" class="form-control pl00 login-password" name="password">
                            <span class="material-input"></span>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-simple btn-login">ログイン</button>
                </div>

                <div class="text-center">
                    <a href="{{ route('get_show_password') }}" class="password-issue pt00 pb00">パスワード再発行</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('javascript')
    @scriptIf('shared.login')
@endpush
