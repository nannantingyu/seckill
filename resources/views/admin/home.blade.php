<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>海贼商城后台管理系统</title>
</head>
<body>
<nav>
    <div class="nav-left"><a href="/">Shopper Admin</a></div>
    <div class="nav-middle"></div>

    <div class="nav-right">
        <div class="user">
            <span id="user">{{ Auth::guard('admin')->user()->name }}</span>
            <div class="dropdown-user" id="drop_down_user">
                <ul>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">退出</a>
                        <form id="logout-form" action="{{ route('adminLogoutPost') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="content">
    <div id="app"></div>
</div>
<script src="{{ asset('/js/admin/app.js') }}"></script>
</body>
</html>