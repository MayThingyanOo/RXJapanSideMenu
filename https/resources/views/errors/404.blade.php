<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>404 Page Not Found | Rx-Japan</title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/non-responsive.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/util.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
</head>

<body class="qb-content-bg">
    <section class="content pt100 mh-100">
        <div class="error-page text-center">
            @if (substr(request()->url(), 0, strlen(url('/user'))) == url('/user'))
                <h1 class="rxjapan-title">RX JAPAN</h1>
                <div class="error-content pt30">
                    <h3>ページが見つかりません</h3>
                    <p class="pt30">
                        指定されたページが見つかりませんでした。
                    </p>
                    <p class="pt30"><a href="{{ route('get_exhibition_list') }}">トップページへ</a></p>
                </div>
            @else
                <div class="error-content pt30">
                    <h3>ページが見つかりません</h3>
                    <p class="pt30">
                        指定されたページが見つかりませんでした。
                    </p>
                </div>
            @endif
        </div>
    </section>
    <footer class="main-footer">
        <div class="container text-center">Copyright © CoPro System Corporation. All Rights Reserved.</div>
    </footer>
</body>

</html>
