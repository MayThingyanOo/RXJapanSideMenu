@extends('rxjapan.layouts.default',['in_exhibition' => true])
@section('title', 'パスワードの変更')
@section('content')

    <div class="page-header qb-page-header qb-sub-page-header">
        <h1 class="qb-align-center">パスワードの変更</h1>
    </div>

    <form method="post" action="{{ route('action_update_password', ['staff_id' => $staff->staff_id]) }}"
        class="form-horizontal">
        {{ csrf_field() }}
        <div class="btn-form-group">
            <div class="btn-double-group">
                <a href="{{ route('get_my_page_list') }}" class="btn btn-default btn-cancel">キャンセル</a>
                <button type="submit" class="btn btn-primary btn-confirm">保存</button>
            </div>
        </div>
        <div class="qb-card-content">
            <div class="qb-tooltip-container">
                <div class="tooltip fade top in qb-tooltip" role="tooltip">
                    <div class="tooltip-arrow"></div>
                    <div class="tooltip-inner-class">
                        新しく設定するパスワードを2回入力したあと、保存ボタンを押してください
                    </div>
                </div>

                <div class="txt-password-notice">
                    半角で英字、数字、記号をそれぞれ1文字以上使用し、8文字以上で入力してください。
                    <span style="color: black;font-size: 110%;" data-html="true" class="qb-tooltip-link"
                        title="使用可能な半角記号<br>! # $ % & ' ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~ ">
                        <i class="material-icons help">help_outline</i>
                    </span>
                </div>
            </div>

            <table class="qb-form-table">
                <tr>
                    <td class="box-10">
                        <label for="form1" class="control-label required">新規パスワード</label>
                    </td>
                    <td class="box-20">
                        <input type="password" class="form-control" name="password" id="form1">
                        @if ($errors->has('password'))
                            {!! CpsForm::printErrorMessage($errors->first('password')) !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="box-10">
                        <label for="form2" class="control-label required">パスワード確認</label>
                    </td>
                    <td class="box-20">
                        <input type="password" class="form-control" name="password_confirmation" id="form2">
                    </td>
                </tr>
            </table>
        </div>
    </form>

@endsection
