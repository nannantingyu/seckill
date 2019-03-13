@extends('layouts.app_shopper')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('订单填写') }}</div>

                <div class="card-body">
                    <form name="order_form" method="POST">
                        @csrf
                        <p>商品名称：{{ $order['title'] }}</p>
                        <p>订单金额：¥{{ $order['order_price'] }}</p>
                        <input type="hidden" name="order_no" value="{{ $order['order_no'] }}">
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('收获地址') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" required autofocus>

                                @if ($errors->has('address'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('电话号码') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" required>

                                @if ($errors->has('phone'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('收货人') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" required>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button class="btn btn-primary" type="button" onclick="submit_order()" >
                                    {{ __('支付') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        function submit_order() {
            $.ajax({
                url: "/fill_order",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_no: document.order_form.order_no.value,
                    address: document.order_form.address.value,
                    phone: document.order_form.phone.value,
                    username: document.order_form.username.value
                },
                success: function(result) {
                    window.location.href = "/order_alipay?order_no=" + document.order_form.order_no.value;
                },
                error: function(err1) {
                    let err_msg = [];
                    if (err1.status === 422) {
                        Array.from(Object.values(err1.responseJSON.errors)).forEach(x=>{
                            err_msg.push(x.join("\n"));
                        });

                        alert(err_msg.join("\n"));
                    }
                }
            });
        }
    </script>
@endsection