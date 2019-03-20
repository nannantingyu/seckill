@extends('layouts.app_html')

@section('content')
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">订单详情</div>

            <div class="card-body">
                <p>{{ $order->title }}</p>
                <p>总价：{{ $order->order_price }}</p>
                <p>收货人：{{ $order->username }}</p>
                <p>电话：{{ $order->phone }}</p>
                <p>收货地址：{{ $order->address }}</p>
                <p><a href="/orderPay?order_no={{$order->order_no}}">去支付</a></p>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection

