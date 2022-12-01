@extends('rxjapan.layouts.auth')
@section('title', 'ログイン')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div
        class="qb-login-box {{ $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password') ? '' : 'animation' }}">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">パスワード再設定</p>
            <p class="font-normal mb20">新しいパスワードを設定してください。</p>
            <div class="txt-password-notice mt00 mb00 text-center">
                半角で英字、数字、記号をそれぞれ1文字以上使用し、8文字以上で入力してください。
                <span data-html="true" class="qb-tooltip-link"
                    title="使用可能な半角記号<br>! # $ % & ' ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~ ">
                    <i class="material-icons help">help_outline</i>
                </span>
            </div>
        </div>

        <div class="login-box-body qb-login-box-body">
            <form method="post" action="{{ route('action_change_password') }}" novalidate>
                {!! csrf_field() !!}
                <span class="material-input"></span>
                <p class="font-normal">現在のパスワードを入力してください</p>
                @if ($errors->has('old_password'))
                    <p class="text-danger">{{ $errors->first('old_password') }}</p>
                @endif
                <div class="box-container box-space-between mb30">
                    <div class="box-10 box-container box-bottom box-center align-center">
                        <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                    </div>
                    <div class="box-90">
                        <div class="form-group label-floating pb00 mt00">
                            <label class="control-label">現在のパスワード</label>
                            <input type="password" class="form-control" name="old_password"
                                value="{{ old('old_password') }}">
                        </div>
                    </div>
                </div>
                <span class="material-input"></span>
                <p class="font-normal">新しいパスワードを入力してください</p>
                @if ($errors->has('new_password'))
                    <p class="text-danger">{{ $errors->first('new_password') }}</p>
                @endif
                <div class="box-container box-space-between mb30">
                    <div class="box-10 box-container box-bottom box-center align-center">
                        <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                    </div>
                    <div class="box-90">
                        <div class="form-group label-floating pb00 mt00">
                            <label class="control-label">新しいパスワード</label>
                            <input type="password" class="form-control" name="new_password"
                                value="{{ old('new_password') }}">
                        </div>
                    </div>
                </div>
                <span class="material-input"></span>
                @if ($errors->has('confirm_password'))
                    <p class="text-danger">{{ $errors->first('confirm_password') }}</p>
                @endif
                <div class="box-container mb40 box-space-between confirm_pas">
                    <div class="box-10 box-container box-bottom box-center align-center">
                        <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                    </div>
                    <div class="box-90">
                        <div class="form-group label-floating pb00 mt00">
                            <label class="control-label">新しいパスワード（確認）</label>
                            <input type="password" class="form-control" name="confirm_password"
                                value="{{ old('confirm_password') }}">
                        </div>
                    </div>
                </div>

                <div class="login-btn-wrap">
                    <a href="{{ route('get_login') }}" class="btn btn-default btn-cancel">キャンセル</a>
                    <button class="btn btn-primary login-btn-confirm" type="submit">送信</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('javascript')
    @scriptIf('shared.login')
@endpush
