@extends('layouts.app_shopper')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('支付失败') }}</div>

                <div class="card-body">
                    <p>支付失败</p>
                    <p><a href="/">重新支付</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")

@endsection