@extends('layouts.app_html')

@section('headscript')
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="section">
            <p class="section-title">最新秒杀</p>
            <ul class="goods-list">
                @foreach ($new_goods as $goods)
                    <li>
                        <img src="{{ json_decode($goods->pictures)[0] }}" alt="">
                        <p class="goods_name"><a href="/kill?id={{$goods->id}}">{{ $goods->title }}</a> </p>
                        <p class="ori_price">原价：¥{{ $goods->ori_price }}</p>
                        <p class="kill_price">秒杀价：¥{{ $goods->kill_price }}</p>
                        <p class="shopper_name">店铺：<a href="/shopper_info?id={{ $goods->shopper_id }}">{{ $goods->nick_name }}</a> </p>
                        <p class="begin_time" data-begin="{{ $goods->begin_time }}" data-end="{{ $goods->end_time }}"></p>
                        <p><a href="/{{get_html_cache_path($goods->id, 'seckill')}}">立即秒杀</a></p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.onload = function() {
            const times = document.getElementsByClassName("begin_time");
            Array.from(times).forEach(obj=>{
                const begin_time = obj.getAttribute('data-begin'), end_time = obj.getAttribute('data-end');
                let time = count_dead_time(begin_time, end_time);
                obj.innerHTML = (time.time < 0?"距结束：" : "距开始：") + time.timeStr;
                setInterval(()=>{
                    let time = count_dead_time(begin_time, end_time);
                    obj.innerHTML = (time.time < 0?"距结束：" : "距开始：") + time.timeStr;
                }, 1000);
            });
        }
    </script>
@endsection
