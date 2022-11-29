<!DOCTYPE html>
<html lang="ja" style="height: 100%;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/img/favicon.ico" />
    @section('title','Rxjapan')
    <title>@yield('title') | Rx-Japan</title>

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/custom_bootstrap.css" rel="stylesheet"> {{-- Overriding Bootstrap CSS --}}
    <link href="/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ auto_version('/css/font-source-han-sans-japanese.css') }}" rel="stylesheet">
    <link href="{{ auto_version('/css/material-icons.css') }}" rel="stylesheet">
    <link href="{{ auto_version('/css/material.css') }}" rel="stylesheet">
    <link href="{{ auto_version('/css/util.css') }}" rel="stylesheet">
    @css('rxjapan')
    @yield('css')
    @cssIf($__view->name)

    <script src="/js/jquery.min.js"></script>
    <script src="{{ auto_version('/js/material.js') }}"></script>
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="qb-content-wrapper qb-login-bg-color">
        <div class="container">
            <section class="login-content">
                @yield('content')
            </section>
        </div>
    </div>
    <footer class="qb-login-footer">
        <span>Copyright © 2017 Coprosystem All Rights Reserved.</span>
    </footer>

    @stack('javascript')
    @scriptIf($__view->name)
</body>

</html>
