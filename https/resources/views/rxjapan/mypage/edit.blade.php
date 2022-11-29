@extends('rxjapan.layouts.default',['in_exhibition' => true])
@section('title', 'アカウント情報の編集')
@section('content')

    <div class="page-header qb-page-header qb-sub-page-header">
        <h1 class="qb-align-center">アカウント情報の編集</h1>
    </div>

    <form method="post" action="{{ route('action_update_my_page') }}" class="form-horizontal">
        {{ csrf_field() }}
        <div class="btn-form-group">
            <div class="btn-double-group">
                <a href="{{ route('get_my_page_list') }}" class="btn btn-default btn-cancel">キャンセル</a>
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
                            {!! CpsForm::printErrorMessage($errors->first('name')) !!}
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
                            {!! CpsForm::printErrorMessage($errors->first('email')) !!}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </form>


@endsection
