<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/img/favicon.ico" />
    @section('title', 'aa')
    <title>@yield('title') | Q-Business</title>

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/custom_bootstrap.css" rel="stylesheet"> {{-- Overriding Bootstrap CSS --}}
    <link href="/bootstrap/plugin/dataTable/css/buttons.dataTables.css" rel="stylesheet">
    <link href="/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/font-icomoon.css" rel="stylesheet">
    <link href="/css/font-source-han-sans-japanese.css" rel="stylesheet">
    <link href="/css/material-icons.css" rel="stylesheet">
    <link href="/css/material.css" rel="stylesheet">
    <link href="/css/non-responsive.css" rel="stylesheet">
    <link href="/css/util.css" rel="stylesheet">
    @css('rxjapan')
    @yield('css')
    @cssIf($__view->name)

    <script src="/js/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/bootstrap/plugin/dataTable/js/jquery.dataTables.min.js"></script>
    <script src="/bootstrap/plugin/dataTable/js/buttons.colVis.js"></script>
    <script src="/bootstrap/plugin/dataTable/js/dataTables.buttons.js"></script>
    <script src="/bootstrap/plugin/dataTable/js/dataTables.colReorder.min.js"></script>
    <script src="/bootstrap/plugin/dataTable/js/dataTables.colResize.js"></script>
    <script src="/plugins/wSelect/wSelect.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/material.js"></script>
    <script src="/js/date.format.js"></script>
    <script src="/js/bootbox.js"></script>
    <script src="/js/jquery.validate.js"></script>
    <script src="{{ auto_version('/js/i18/ja.js') }}"></script>
    <script src="{{ auto_version('/js/lib.js') }}"></script>
    <script src="{{ auto_version('/js/main.js') }}"></script>
    @stack('ahead_javascript')
</head>

<body class="{{ empty($in_exhibition) ? 'qb-bg-img' : 'qb-content-bg' }}">
    <div class="qb-mh-100">
        <div class="navbar-fixed-top">
            <!--HEADER-->
            <header class="navbar qb-navbar qb-bg"
                @if (CpsAuth::user()->user->banner_color == '#00a6ff') style="background-color: #ffffff" @else style="background-color: {{ CpsAuth::user()->user->banner_color }}" @endif>
                <div class="navbar-header"
                    @if (CpsAuth::user()->user->banner_color) style="background-color: {{ CpsAuth::user()->user->banner_color }} !important" @else style="background-color: #00A6FF" @endif>
                    <a class="navbar-brand qb-navbar-brand withoutripple {{ CpsAuth::user()->user->logo ? 'has-logo' : '' }}"
                        href="{{ route('get_exhibition_list', ['tab' => $h_exhibition->exhibition_group_id ?? \Route::input('exhibition_group_id')]) }}">
                        @if (CpsAuth::user()->user->logo)
                            <img id="cps_logo"
                                src={{ CpsFile::tmpUrl(['logo/' . CpsAuth::user()->user->id, CpsAuth::user()->user->logo], '+2 days') }}>
                        @else
                            <img src="/img/Q_Business_type.png">
                        @endif
                    </a>
                </div>
                <div class="qb-navbar-new">
                    <ul class="nav navbar-nav qb-nav qb-navbar-nav navbar-right">
                        <li class="dropdown">
                            <a class="qb-dropdown-toggle" aria-expanded="false" aria-haspopup="true" role="button"
                                data-bs-toggle="dropdown" href="#">
                                <span class="fl pt05 pr05">{{ CpsAuth::user()->name }} さん</span>&nbsp;
                                <span class="user-btn"></span>
                            </a>
                            <ul class="dropdown-menu qb-dropdown-menu">
                                <li>
                                    <a href=""> アカウント情報 </a>
                                </li>
                                <li>
                                    <a href=""> ログアウト </a>
                                </li>
                            </ul>
                        </li>
                        <li class="guide">
                            <a href="{{ guide_url('menu') }}" target="_blank">
                                <span class="icon-guide qb-tooltip-link" data-placement="bottom" title="使い方ガイド"></span>
                            </a>
                        </li>
                    </ul>
                    @yield('navbar')
                </div>
            </header>
            <!--HEADER END-->
            @stack('header')

            @if (session('flash_message'))
                <div class="callout callout-info flash-message-dialog">
                    <p>{!! nl2br(e(session('flash_message'))) !!} </p>
                </div>
            @endif
        </div>
        <!-- Full Width Column -->
        <!-- Main content -->
        <section class="content @hasSection('header') inExhibition @endif">
            @yield('content')
        </section>
        @dump($errors->getMessages(), true, ['style' => 'position: fixed;right: 0;bottom: 0;'])
    </div>
    <!-- /.content -->
    <footer class="main-footer">
        <div class="footer-text">Copyright © 2017 Coprosystem All Rights Reserved.</div>
    </footer>
    @stack('javascript')
    @scriptIf($__view->name)
</body>

</html>
