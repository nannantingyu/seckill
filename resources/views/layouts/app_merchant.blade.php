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
    <script src="{{ mix('js/common.js') }}"></script>

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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('merchantGoodsList') }}">全部商品</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('merchantFlashSaleList') }}">秒杀商品</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown login_hide">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                登陆 <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('login') }}">用户登陆</a>
                                <a class="dropdown-item" href="{{ route('merchantLogin') }}">商家登陆</a>
                                <a class="dropdown-item" href="{{ route('adminLogin') }}">管理员登陆</a>
                            </div>
                        </li>
                        <li class="nav-item login_hide">
                            <a class="nav-link" href="{{ route('merchantRegister') }}">商家注册</a>
                        </li>
                        <li class="nav-item dropdown login_show">
                            <a id="navbarDropdownLogined" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span id="login_user_name"></span><span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLogined">
                                <a class="dropdown-item" href="#"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('退出') }}
                                </a>

                                <form id="logout-form" action="{{ route('merchantLogout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
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
        function loginStateSwitch() {
            const merchant_name = tool.getCookie('merchant_name');
            if (merchant_name) {
                $('.login_hide').hide();
                $('.login_show').show();
                $('#login_user_name').text(merchant_name);
            }
            else {
                $('.login_hide').show();
                $('.login_show').hide();
            }
        }
        loginStateSwitch();
    </script>
</body>
</html>
