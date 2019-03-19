@extends('layouts.app_shopper')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/seckill_goods_list">首页</a></li>
        <li class="active">商品列表</li>
    </ol>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th>商品名称</th>
                    <th>商品产地</th>
                    <th>商品重量</th>
                    <th>商品包装</th>
                    <th>商品品牌</th>
                    <th>商品图片</th>
                    <th>操作</th>
                </tr>
                @foreach($goods as $seckill_goods)
                    <tr>
                        <td><a href="{{ route('seckill_goods_update', ['id'=>$seckill_goods->goods_id]) }}">{{$seckill_goods->goods_name}}</a></td>
                        <td>{{$seckill_goods->place}}</td>
                        <td>{{$seckill_goods->weight}}</td>
                        <td>{{$seckill_goods->packing}}</td>
                        <td>{{$seckill_goods->brand}}</td>
                        <td>
                            @foreach( json_decode($seckill_goods->detail_pictures) as $picture)
                                <img width="100px" src="{{$picture}}" alt="{{$seckill_goods->goods_name}}">
                            @endforeach
                        </td>
                        <td>
                            <a href="{{route('seckill_goods_del',['id'=>$seckill_goods->goods_id])}}">删除</a>
                            <a href="{{route('apply_for_seckill',['id'=>$seckill_goods->goods_id])}}">申请秒杀</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-4">
            <span>欢迎你,{{ShopperAuth::shopper()->nick_name}} </span>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{route('seckill_goods_add')}}">添加商品</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
