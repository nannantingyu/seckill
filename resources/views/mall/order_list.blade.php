@extends('layouts.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li><a href="{{get_html_cache_path('kill_home', 'seckill')}}">秒杀列表</a></li>
        <li>订单列表</li>
    </ol>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>商品名称</th>
                    <th>订单价格</th>
                    <th>收货人姓名</th>
                    <th>收货人电话</th>
                    <th>收获地址</th>
                    <th>支付状态</th>
                    <th>订单编号</th>
                    <th>支付时间</th>
                    <th>操作</th>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->title}}</td>
                        <td>{{$order->order_price}}</td>
                        <td>{{$order->username}}</td>
                        <td>{{$order->phone}}</td>
                        <td>{{$order->address}}</td>
                        <td>{{$order->pay_status==0?"为支付":($order->pay_status==1?"已支付":"已退款")}}</td>
                        <td>{{$order->order_no}}</td>
                        <td>{{$order->pay_time}}</td>
                        <td>
                            @if($order->pay_status == 1)
                            <a href="{{route('orderDelete',['order_no'=>$order->order_no])}}">删除订单</a>
                            @elseif($order->pay_status == 0)
                            <a href="{{route('orderCancel',['order_no'=>$order->order_no])}}">取消订单</a>
                            <a href="{{route('orderInfo',['order_no'=>$order->order_no])}}">去支付</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
