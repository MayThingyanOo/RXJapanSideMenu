@extends('rxjapan.layouts.default')
@section('title', 'イベント一覧')
@section('content')
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
                    <table class="qb-exhibition-list {{ $exhibition_group->id == $exhibition_group_id ? 'qb-open' : '' }}"
                        @if ($banner_color) style="background-color: {{ $banner_color }}" @else style="background-color: #00A6FF" @endif>
                        <thead class="header">
                            <tr class="qb-exhibition-list-header">
                                @php($ids = $exhibition_group->exhibitions->pluck('id')->all())
                                @php($before = 0)
                                @php($insession = 0)
                                @php($after = 0)
                                <th class="corner corner-left group-name pl15 pr15"
                                    @if ($banner_color) style="background-color: {{ $banner_color }}" @else style="background-color: #00A6FF" @endif>
                                    <p>{{ $exhibition_group->group_name }}</p>
                                    @foreach ($exhibition_group->exhibitions->sortBy('exhibition_id') as $key => $exhibition)
                                        @if ($exhibition->status_code == 1)
                                            @php($before += 1)
                                        @endif
                                        @if ($exhibition->status_code == 2)
                                            @php($insession += 1)
                                        @endif
                                        @if ($exhibition->status_code == 3)
                                            @php($after += 1)
                                        @endif
                                    @endforeach
                                    <ul class="flex">
                                        <li>開催前 <span>{{ isset($before) ? $before : 0 }}</span>件</li>
                                        <li>開催中 <span>{{ isset($insession) ? $insession : 0 }}</span>件</li>
                                        <li>終了 <span>{{ isset($after) ? $after : 0 }}</span>件</li>
                                    </ul>
                                </th>
                                <th
                                    @if ($banner_color) style="background-color: {{ $banner_color }}" @else style="background-color: #00A6FF" @endif>
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
                                                    <tr class="_link" data-url="">
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
                                                            @foreach ($exhibition->exhibitionDates as $exhibition_date)
                                                                @if ($loop->first)
                                                                    開始　{{ $exhibition_date->day }}　{{ $exhibition_date->open_time }}<br>
                                                                @endif
                                                                @if ($loop->last)
                                                                    終了　{{ $exhibition_date->day }}　{{ $exhibition_date->end_time }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                @if ($exhibition_group->exhibitions->count() < 1)
                                                    <tr class="row-add-schedule">
                                                        <th colspan="6">
                                                            <div>
                                                                セッション追加の権限がありません。
                                                            </div>
                                                        </th>
                                                    </tr>
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
@endsection
