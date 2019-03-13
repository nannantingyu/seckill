@extends('layouts.app_shopper')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <span>欢迎你,{{ShopperAuth::shopper()->nick_name}} </span><a href="{{route('seckill_goods_add')}}">添加商品</a>
            </div>
            <ul class="list-group">
                @foreach($goods as $seckill_goods)
                <li class="list-group-item">
                    <div><span>商品名称</span>: <a href="{{ route('seckill_goods_update', ['id'=>$seckill_goods->id]) }}">{{$seckill_goods->goods_name}}</a></div>
                    <div><span>商品产地</span>: {{$seckill_goods->place}}</div>
                    <div><span>商品重量</span>: {{$seckill_goods->weight}}</div>
                    <div><span>商品包装</span>: {{$seckill_goods->packing}}</div>
                    <div><span>商品品牌</span>: {{$seckill_goods->brand}}</div>
                    @foreach( json_decode($seckill_goods->detail_pictures) as $picture)
                        <img width="200px" src="{{$picture}}" alt="{{$seckill_goods->goods_name}}">
                    @endforeach

                    <div class="pull-right">
                        <a href="{{route('seckill_goods_del',['id'=>$seckill_goods->id])}}">删除</a>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
