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
                @if (CpsAuth::isSuperUser())
                    右上の「イベント新規作成」からイベントを作成してください。
                @else
                    現在ログインされているユーザではイベント作成権限がありません。イベント作成権限があるユーザにてログインをしてください。
                @endif
            </div>
        @else
            @foreach ($exhibition_groups as $key => $exhibition_group)
                <div class="qb-exhibition-list-wrapper">
                    <table
                        class="qb-exhibition-list {{ $exhibition_group->id == ($open ?: $exhibition_groups->pluck('id')->first()) ? 'qb-open' : '' }}"
                        @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>
                        <thead class="header">
                            <tr class="qb-exhibition-list-header">
                                @php($ids = $exhibition_group->exhibitions->pluck('id')->all())
                                @php($before = 0)
                                @php($insession = 0)
                                @php($after = 0)
                                <th class="corner corner-left group-name pl15 pr15" @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>
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
                                {{-- <th>@if (!CpsAuth::isSuperUser())<span class="tag">{{CpsAuth::getRightNameInExhibitionGroup($exhibition_group)}}</span>@else &nbsp; @endif</th> --}}
                                <th @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif><span class="fz-xlg_ttl">総申込数</span> <span
                                        class="fz-xlg">{{ $visitors_count->only($ids)->sum() }}</span> 人</th>
                                <th @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif><span class="fz-xlg_ttl">総来場数</span> <span
                                        class="fz-xlg">{{ $entries_count->only($ids)->sum() }}</span> 人</th>
                                @if (CpsAuth::isSuperUser())
                                    <th class="qb-js-event-off" @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>
                                        <div class="dropdown">
                                            <button type="button" class="border qb-btn dropdown-btn" aria-expanded="false"
                                                aria-haspopup="true" role="button" data-bs-toggle="dropdown"
                                                @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>イベント編集</button>
                                            <ul class="dropdown-menu qb-exhibition-dropdown-menu event-edit">
                                                <li class="_link"
                                                    data-url="{{ route('qb_staff_list', ['exhibition_group_id' => $exhibition_group->exhibition_group_id]) }}">
                                                    スタッフ管理</li>
                                                @if ($exhibition_group->isAuthorityDivisionFunctionEnabled())
                                                    <li class="_link"
                                                        data-url="{{ route('qb_orgs_detail', ['exhibition_group_id' => $exhibition_group->id]) }}">
                                                        組織設定</li>
                                                @endif
                                                @if (CpsAuth::isSuperUser())
                                                    <li class="_exhibition_group_edit"
                                                        data-url="{{ route('qb_ex_gp_edit', [$exhibition_group->exhibition_group_id]) }}"
                                                        data-id="{{ $exhibition_group->id }}"
                                                        data-name="{{ $exhibition_group->group_name }}">イベントタイトル変更</li>
                                                    <li class="_exhibition_group_function"
                                                        data-url="{{ route('qb_ex_gp_edit', [$exhibition_group->exhibition_group_id]) }}"
                                                        data-id="{{ $exhibition_group->id }}"
                                                        data-name="{{ $exhibition_group->group_name }}">オプション機能確認</li>
                                                    <li class="_link ex_group_copy"
                                                        data-url="{{ route('qb_ex_gp_copy', ['exhibition_group_id' => $exhibition_group->exhibition_group_id]) }}">
                                                        このイベントの複製</li>
                                                    <li data-type="exhibition_group" data-title="イベントの削除"
                                                        class="_can_delete {{ $insession >= 1 ? 'open_alert' : ''  }}"
                                                        data-name="{{ $exhibition_group->group_name }}"
                                                        data-url="{{ route('qb_ex_gp_delete', [$exhibition_group->exhibition_group_id]) }}">
                                                        このイベントの削除</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </th>
                                @endif
                                <th @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>
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
                                                    <th>申込者数</th>
                                                    <th>来場者数</th>
                                                    @if (CpsAuth::isSuperUser())
                                                        <th>&nbsp;</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($exhibition_group->exhibitions->sortBy('exhibition_id') as $key => $exhibition)
                                                    <tr class="_link"
                                                        data-url="{{ route('qb_exhibition_top', ['exhibition_id' => $exhibition->exhibition_id]) }}">
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
                                                        <td class="text-right">
                                                            <span
                                                                class="fz-lg">{{ $visitors_count->get($exhibition->id) ?: 0 }}</span>
                                                            人
                                                        </td>
                                                        <td class="text-right">
                                                            <span
                                                                class="fz-lg">{{ $entries_count->get($exhibition->id) ?: 0 }}</span>
                                                            人
                                                        </td>
                                                        @if (CpsAuth::isSuperUser())
                                                            <td class="text-center">
                                                                <div>
                                                                    <div class="dropdown qb-js-event-off">
                                                                        <button type="button"
                                                                            class="qb-btn dropdown-btn btn btn-primary-reverse"
                                                                            aria-expanded="false" aria-haspopup="true"
                                                                            role="button" data-bs-toggle="dropdown"
                                                                            @if (CpsAuth::user()->user->banner_color) style="border-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="border-color: #00A6FF" @endif>編集</button>
                                                                        <ul
                                                                            class="dropdown-menu qb-exhibition-dropdown-menu qb-dropdown-menu-blue setting-edit" data-popper-placement="bottom">
                                                                            <li class="_link"
                                                                                data-url="{{ route('qb_copy_exhibition', ['exhibition_id' => $exhibition->exhibition_id]) }}">
                                                                                このセッションの複製</li>
                                                                            <span class="tool-tip"
                                                                                data-bs-toggle="tooltip" data-placement="top"
                                                                                title="">
                                                                                @token
                                                                                <li data-type="visitors"
                                                                                    data-title="申込者の全件削除"
                                                                                    class="_can_delete_visitor {{ $exhibition->isHolding() ? 'open_alert' : '' }}"
                                                                                    data-name="{{ $exhibition->name }}"
                                                                                    data-attr={{ $exhibition->exhibition_group_id }}
                                                                                    data-url="{{ route('qb_action_delete_visitor_all', ['exhibition_id' => $exhibition->exhibition_id]) }}">
                                                                                    このセッション申込者の全件削除</li>
                                                                            </span>
                                                                            <li data-type="exhibition" data-title="セッションの削除"
                                                                                class="_can_delete {{ $exhibition->isHolding() ? 'open_alert' : '' }}"
                                                                                data-name="{{ $exhibition->name }}"
                                                                                data-url="{{ route('qb_action_delete_exhibition', ['exhibition_id' => $exhibition->exhibition_id]) }}">
                                                                                このセッションの削除</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                @if (CpsAuth::isSuperUser())
                                                    <tr class="row-add-schedule">
                                                        <th colspan="6">
                                                            <div>
                                                                @if ($exhibition_group->exhibitions->count() < 1)
                                                                    セッションを右のボタンより追加してください。
                                                                @endif
                                                                <div class="button-add">
                                                                    <a class="btn btn-primary btn-create btn-add"
                                                                        href="{{ route('qb_ex_create_step1', ['exhibition_group_id' => $exhibition_group->exhibition_group_id]) }}"
                                                                        @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important;box-shadow: none" @else style="background-color: #00A6FF" @endif>セッションの追加
                                                                        <i class="material-icons">add</i></a>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                @else
                                                    @if ($exhibition_group->exhibitions->count() < 1)
                                                        <tr class="row-add-schedule">
                                                            <th colspan="6">
                                                                <div>
                                                                    セッション追加の権限がありません。
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    @endif
                                                @endif
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
