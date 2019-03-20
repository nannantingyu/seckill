@extends('layouts.app_merchant')
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('merchantGoodsList') }}">首页</a></li>
        <li class="active">秒杀列表</li>
    </ol>
    <div class="row">
        <div class="col-md-2">
            <a href="{{route('merchantGoodsList')}}">添加秒杀商品</a>
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
                @foreach($goods as $g)
                    <tr>
                        <td><a href="{{ route('merchantFlashSaleUpdate', ['id'=>$g->id]) }}">{{$g->title}}</a></td>
                        <td>{{$g->description}}</td>
                        <td>
                            @foreach( json_decode($g->pictures) as $picture)
                                <img width="100px" src="{{$picture}}" alt="{{$g->title}}">
                            @endforeach
                        </td>
                        <td>{{$g->ori_price}}</td>
                        <td>{{$g->kill_price}}</td>
                        <td>{{$g->begin_time}}</td>
                        <td>{{$g->end_time}}</td>
                        <td>{{$g->quantity}}</td>
                        <td>{{$g->stock}}</td>
                        <td>{{$g->state === 1?($g->begin_time > date('Y-m-d H:i:s') ? '未开始':($g->end_time > date('Y-m-d H:i:s')?'正在进行':'已结束')):($g->state === -1?'未通过':'未审核')}}</td>
                        <td style="width: 120px">
                            <a href="{{route('merchantFlashSaleDelete',['id'=>$g->id])}}">删除</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
@section('script')
@endsection
