<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('plugin/jquery.js') }}"></script>
    <script src="{{ mix('js/page.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/page.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav nav-tabs">
                        @if(ShopperAuth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">商品</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/seckill_goods_list">全部商品</a>
                                <a class="dropdown-item" href="/seckill_list">秒杀商品</a>
                            </div>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown unlogined">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                登陆 <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/login">用户登陆</a>
                                <a class="dropdown-item" href="/shopper_login">商家登陆</a>
                                <a class="dropdown-item" href="/ad/login">管理员登陆</a>
                            </div>
                        </li>
                        <li class="nav-item unlogined">
                            <a class="nav-link" href="/shopper_register">商家注册</a>
                        </li>
                        <li class="nav-item dropdown logined">
                            <a id="navbarDropdown2" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown2">
                                <a class="dropdown-item" href="/logout"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">退出
                                </a>
                                <form id="logout-form" action="/logout" method="POST" style="display: none;"></form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container-fluid">
            @yield('content')
            </div>
        </main>
    </div>
@yield('script')
    <script>
        const username = cookie_tool.getCookie('username');
        if(username) {
            $("#navbarDropdown2 span").text(username);
            $(".logined").show();
            $(".unlogined").hide();
        }
        else {
            $(".logined").hide();
            $(".unlogined").show();
        }
    </script>
    <script src="{{ asset('js/common.js') }}?id=534213342424"></script>
</body>
</html>
