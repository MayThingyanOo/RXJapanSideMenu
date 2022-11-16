@extends('rxjapan.layouts.auth')
@section('title', 'パスワード再設定')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div class="qb-login-box {{ $errors->has('password') || $errors->has('hash') ? '' : 'animation' }}">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">パスワード再設定</p>
            <div class="txt-password-notice mt00 mb00 text-center">
                半角で英字、数字、記号をそれぞれ1文字以上使用し、8文字以上で入力してください。
                <span data-html="true" class="qb-tooltip-link"
                    title="使用可能な半角記号<br>! # $ % & ' ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~ ">
                    <i class="material-icons help">help_outline</i>
                </span>
            </div>
        </div>

        <div class="login-box-body qb-login-box-body">
            <div class="box-body">
                @if ($errors->has('password'))
                    <div class="alert alert-danger mb30">{{ $errors->first('password') }}</div>
                @endif

                @if ($errors->has('hash'))
                    <div class="alert alert-danger mb30">{{ $errors->first('hash') }}</div>
                @endif

                <form method="post" action="" novalidate>
                    {!! csrf_field() !!}
                    <input type="hidden" name="hash" value="{{ $hash }}">

                    <div class="box-container box-space-between mb30">
                        <div class="box-10 box-container box-bottom box-center align-center">
                            <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                        </div>
                        <div class="box-90">
                            <div class="form-group label-floating pb00 mt00">
                                <label class="control-label">新しいパスワード</label>
                                <input type="password" class="form-control" name="password" value="{{ old('password') }}">
                                <span class="material-input"></span>
                            </div>
                        </div>
                    </div>

                    <div class="box-container box-space-between mb40 confirm-password">
                        <div class="box-10 box-container box-bottom box-center align-center">
                            <i class="fa fa-solid fa-lock md-gray font-24" aria-hidden="true"></i>
                        </div>
                        <div class="box-90">
                            <div class="form-group label-floating pb00 mt00">
                                <label class="control-label">新しいパスワード（確認）</label>
                                <input type="password" class="form-control" name="password_confirmation">
                                <span class="material-input"></span>
                            </div>
                        </div>
                    </div>

                    <div class="login-btn-wrap">
                        <button class="btn btn-primary login-btn-confirm" type="submit">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    @scriptIf('shared.login')
@endpush
