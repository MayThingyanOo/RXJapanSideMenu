@extends('rxjapan.layouts.default',['in_exhibition' => true])
@section('title', 'アカウント情報')
@section('content')

    <div class="page-header qb-page-header qb-sub-page-header">
        <h1 class="qb-align-center">アカウント情報</h1>
    </div>
    <div class="btn-double-group">
        <a href="{{ route('get_my_page_edit') }}" class="btn btn-primary btn-confirm btn-edit">編集</a>
    </div>
    <div class="qb-card-content">
        <table class="qb-form-table">
            <tr>
                <td class="box-10">スタッフID</td>
                <td class="box-20">{{ $staff->staff_id }}</td>
            </tr>
            <tr>
                <td class="box-10">氏名</td>
                <td class="box-20">{{ $staff->name }}</td>
            </tr>
            <tr>
                <td class="box-10">メールアドレス</td>
                <td class="box-20">{{ $staff->email }}</td>
            </tr>
            <tr>
                <td class="box-10">パスワード</td>
                <td class="box-20"><a class="btn-edit-password qb-btn btn btn-primary-reverse"
                        href="{{ route('get_edit_password_form') }}">パスワード変更</a></td>
            </tr>
        </table>
    </div>
@endsection
