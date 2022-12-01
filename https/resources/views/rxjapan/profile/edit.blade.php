@extends('rxjapan.layouts.default')
@section('title', 'アカウント情報の編集')
@section('content')

    <div class="page-header qb-page-header qb-sub-page-header">
        <h1 class="qb-align-center">アカウント情報の編集</h1>
    </div>

    <form method="post" action="{{ route('action_profile_update') }}" class="form-horizontal">
        {{ csrf_field() }}
        <div class="btn-form-group">
            <div class="btn-double-group">
                <a href="{{ route('get_user_profile') }}" class="btn btn-default btn-cancel">キャンセル</a>
                <button type="submit" class="btn btn-primary btn-confirm">保存</button>
            </div>
        </div>

        <div class="qb-card-content">
            <table class="qb-form-table">
                <tr>
                    <td class="box-10">
                        <label for="form1" class="control-label">氏名</label>
                    </td>
                    <td class="box-20">
                        <input type="text" class="form-control" name="name" id="form1"
                            value="{{ old('name', $staff->name) }}">
                        @if ($errors->has('name'))
                            {!! printErrorMessage($errors->first('name')) !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="box-10">
                        <label for="form2" class="control-label">メールアドレス</label>
                    </td>
                    <td class="box-20">
                        <input type="text" class="form-control" id="form2" value="{{ old('email', $staff->email) }}"
                            readonly>
                        @if ($errors->has('email'))
                            {!! printErrorMessage($errors->first('email')) !!}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </form>
@endsection
