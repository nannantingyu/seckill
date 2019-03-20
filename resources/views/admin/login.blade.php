@extends('layouts.app_admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('管理员登陆') }}</div>

                    <div class="card-body">
                        <form method="POST" onsubmit="return postLogin();">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('用户名') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密码') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('登陆') }}
                                    </button>
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
                name: $('#name').val(),
                password: $('#password').val(),
            };

            const error = tool.validate({
                'name': 'required|min:3',
                'password': 'required|min:4'
            }, formValue, null, true);

            if (error) {
                alert(error);
                console.error(error);
                return false;
            }

            $.ajax({
                url: '/admin/login',
                type: 'post',
                data: formValue,
                success: result=> {
                    if (result.code === 400000) {
                        tool.setCookie('username', result.login_user);
                        window.location.href = "{{ route('adminHome') }}";
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