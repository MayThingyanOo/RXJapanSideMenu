@extends('rxjapan.layouts.auth')
@section('title', 'パスワード再発行')

@section('css')
    @cssIf('shared.login')
@endsection

@section('content')
    <div class="qb-login-box {{ $errors->has('email') ? '' : 'animation' }}">
        <div class="qb-login-header">
            <h1 class="login-title">RX JAPAN</h1>
            <p class="login-txt">パスワード再発行</p>
            <p class="font-normal mb00">ご登録いただいているメールアドレスを入力して<br>送信ボタンを押してください。<br>パスワード再設定用のURLを送信します。</p>
        </div>

        <div class="login-box-body qb-login-box-body">
            <div class="box-body">
                @if ($errors->has('email'))
                    <div class="alert alert-danger mb30">{{ $errors->first('email') }}</div>
                @endif

                <form method="post" action="{{ route('action_send_reminder_mail') }}" novalidate>
                    {!! csrf_field() !!}

                    <div class="box-container box-space-between mb40">
                        <div class="box-10 box-container box-bottom box-center align-center">
                            <i class="fa fa-solid fa-envelope md-gray"></i>
                        </div>
                        <div class="box-90">
                            <div class="form-group label-floating {{ old('email') ? 'has-error' : '' }} pb00 mt00">
                                <label class="control-label">メールアドレス</label>
                                <input type="email" class="form-control pl00" name="email" value="{{ old('email') }}">
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
    </div>
@endsection

@push('javascript')
    @scriptIf('shared.login')
@endpush
