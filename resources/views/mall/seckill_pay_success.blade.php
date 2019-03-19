@extends('layouts.app_shopper')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('支付成功') }}</div>

                <div class="card-body">
                    <p>恭喜您，支付成功</p>
                    <p><a href="/">浏览其他秒杀</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")

@endsection