@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('用户登陆') }}</div>

                <div class="card-body">
                    <form method="POST" onsubmit="return postLogin();">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('邮箱地址') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密码') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        {{ __('记住我') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登陆') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('忘记密码?') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function postLogin() {
            const formValue = {
                email: $('#email').val(),
                password: $('#password').val(),
                remember: $('#remember').prop('checked')
            };

            const error = tool.validate({
                'email': 'required|min:5',
                'password': 'required|min:6'
            }, formValue, null, true);

            if (error) {
                alert(error);
                console.error(error);
                return false;
            }

            $.ajax({
                url: '/login',
                type: 'post',
                data: formValue,
                success: result=> {
                    if (result.code === 400000) {
                        tool.setCookie('username', result.login_user);
                        window.location.href = "{{ route('/') }}";
                    }
                    else {
                        alert(result.message);
                    }
                },
                error: error=> {
                    console.log(error);
                }
            });
            return false;
        }
    </script>
@endsection
