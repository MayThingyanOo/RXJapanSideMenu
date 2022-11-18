@extends('rxjapan.layouts.default')
@section('title', 'イベント一覧')
@section('content')
    <!--CONTENT-->
    <div class="container qb-container" role="main">
        <nav class="nav qb-nav clearfix">
            <div class="event-head-title">
                <h1 class="qb-c-w exhibition-list-top">イベント一覧</h1>
            </div>
        </nav>
        @if ($exhibition_groups->isEmpty())
            <div class="qb-exhibition-list-empty">
                インされているユーザではイベント作成権限がありません。イベント作成権限があるユーザにてログインをしてください。
            </div>
        @else
            @foreach ($exhibition_groups as $key => $exhibition_group)
                <div class="qb-exhibition-list-wrapper">
                    <table
                        class="qb-exhibition-list {{ $exhibition_group->id == ($open ?: $exhibition_groups->pluck('id')->first()) ? 'qb-open' : '' }}"
                        style="background-color: #00A6FF">
                        <thead class="header">
                            <tr class="qb-exhibition-list-header">
                                @php($ids = $exhibition_group->exhibitions->pluck('id')->all())
                                @php($before = 0)
                                @php($insession = 0)
                                @php($after = 0)
                                <th class="corner corner-left group-name pl15 pr15" style="background-color: #00A6FF">
                                    <p>{{ $exhibition_group->group_name }}</p>
                                    @foreach ($exhibition_group->exhibitions->sortBy('exhibition_id') as $key => $exhibition)
                                        @if ($exhibition->status_code == 1) @php( $before += 1 ) @endif
                                        @if ($exhibition->status_code == 2) @php( $insession += 1 ) @endif
                                        @if ($exhibition->status_code == 3) @php( $after += 1 ) @endif
                                    @endforeach
                                    <ul class="flex">
                                        <li>開催前 <span>{{ isset($before) ? $before : 0 }}</span>件</li>
                                        <li>開催中 <span>{{ isset($insession) ? $insession : 0 }}</span>件</li>
                                        <li>終了 <span>{{ isset($after) ? $after : 0 }}</span>件</li>
                                    </ul>
                                </th>
                                <th style="background-color: #00A6FF">
                                    <a href="#" class="qb-exhibition-list-open-btn"><span class="arrow"></span></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="corner">
                                    <!--INNER TABLE-->
                                    <div class="qb-exhibition-list-body qb-exhibition-inner-list">
                                        <p>セッション</p>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>セッション名/開催場所</th>
                                                    <th>開催期間</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($exhibition_group->exhibitions->sortBy('exhibition_id') as $key => $exhibition)
                                                    <tr class="_link"
                                                        data-url="">
                                                        <td class="qb-exhibition-list-id">
                                                            {{ $exhibition_group->order . '-' . sprintf('%02d', $exhibition->order) }}
                                                        </td>
                                                        <td>
                                                            <div class="venue">
                                                                @if ($exhibition->status_code == 1)
                                                                    <span
                                                                        class="label qb-label-before">{{ $exhibition->status_name }}</span>
                                                                @elseif($exhibition->status_code == 2)
                                                                    <span
                                                                        class="label qb-label-insession">{{ $exhibition->status_name }}</span>
                                                                @elseif($exhibition->status_code == 3)
                                                                    <span
                                                                        class="label qb-label-after">{{ $exhibition->status_name }}</span>
                                                                @endif
                                                                <span
                                                                    class="event-ttl">{{ $exhibition->name }}<br>{{ $exhibition->place }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @foreach ($exhibition->exhibition_dates as $exhibition_date)
                                                                @if ($loop->first)
                                                                    開始　{{ $exhibition_date->day }}　{{ $exhibition_date->open_time }}<br>
                                                                @endif
                                                                @if ($loop->last)
                                                                    終了　{{ $exhibition_date->day }}　{{ $exhibition_date->end_time }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--INNER TABLE END-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>
    <!--delete dialog-->
    <div id="delete_content_container" class="dis-none">
        <p class="qb-warning-box dis-none" id="visitors">
            <span class="txt-red">全ての申込者の情報と受付履歴などの関連情報を削除します。</span><br> 本当に削除する場合は下記にセッションのタイトル<br><span
                class="qb-notification-text"></span><br>と入力してください。<br>※削除完了後にログインIDとして登録しているアドレス宛に、<br>no-reply@q-pass.jpより完了メールが送信されます。<br>
        </p>
        <p class="qb-warning-box dis-none" id="exhibition_group">
            <i class="material-icons qb-warning-icon">error</i>
            <span class="event-title txt-red">このイベントとイベントに関連する全てのデータを削除します。<br>※一度削除したイベントは復元できません。</span><br>
            削除する場合は、確認のため再度イベント名を入力し、<br>削除ボタンを押してください。<br><span class="qb-notification-text qb-event-text"></span><br>
            <label for="form1" class="event-label">イベント名</label>
        </p>
        <p class="qb-warning-box dis-none" id="exhibition">
            <span class="txt-red">セッション情報と関連するデータの全てを削除します。※復元できません</span><br> 本当に削除する場合は下記にセッションのタイトル<br><span
                class="qb-notification-text"></span><br>と入力してください。<br>
        </p>
        <div class="form-horizontal form-modal-input">
            <input type="text" placeholder="削除するイベント名" name="name" class="form-control" autocomplete="off">
        </div>
    </div>
    <!--edit exhibition group-->
    <div id="exhibition_group_edit_container"  class="dis-none">
        <div class="exhibition_group_edit form-horizontal form-modal-input">
            <div class="form-group box-container">
                <label for="group_name" class="control-label box-35 pr15">イベント名</label>
                <div class="box-50">
                    <input id="group_name" type="text" name="group_name" class="form-control" required="true">
                    <div id="group_name_error"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @token
    @scope([
    'error_message' => cps_trans('validation'),
    'groups' => $exhibition_groups->pluck('enabled_services', 'id'),
    ])
@endsection
