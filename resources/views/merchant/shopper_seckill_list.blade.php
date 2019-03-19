@extends('layouts.app_shopper')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/seckill_goods_list">首页</a></li>
        <li class="active">秒杀列表</li>
    </ol>
    <div class="row">
        <div class="col-md-10">
            <span>欢迎你,{{ShopperAuth::shopper()->nick_name}} </span>
        </div>
        <div class="col-md-2">
            <a href="{{route('seckill_goods_list')}}">添加秒杀商品</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>商品标题</th>
                    <th>商品描述</th>
                    <th>商品图片</th>
                    <th>商品原价</th>
                    <th>秒杀价格</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>总数量</th>
                    <th>库存数量</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                @foreach($goods as $seckill)
                    <tr>
                        <td><a href="{{ route('seckill_update', ['id'=>$seckill->id]) }}">{{$seckill->title}}</a></td>
                        <td>{{$seckill->description}}</td>
                        <td>
                            @foreach( json_decode($seckill->pictures) as $picture)
                                <img width="100px" src="{{$picture}}" alt="{{$seckill->title}}">
                            @endforeach
                        </td>
                        <td>{{$seckill->ori_price}}</td>
                        <td>{{$seckill->kill_price}}</td>
                        <td>{{$seckill->begin_time}}</td>
                        <td>{{$seckill->end_time}}</td>
                        <td>{{$seckill->quantity}}</td>
                        <td>{{$seckill->stock}}</td>
                        <td>{{$seckill->state === 1?($seckill->begin_time > date('Y-m-d H:i:s') ? '未开始':($seckill->end_time > date('Y-m-d H:i:s')?'正在进行':'已结束')):($seckill->state === -1?'未通过':'未审核')}}</td>
                        <td style="width: 120px">
                            <a href="{{route('seckill_goods_del',['id'=>$seckill->id])}}">删除</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        console.log(cookie_tool.getCookie('name'));
    </script>
@endsection
