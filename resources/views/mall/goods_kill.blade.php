@extends('layouts.app_html')

@section('content')
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header"><span class="begin_time" data-begin="{{ $seckill->begin_time }}" data-end="{{ $seckill->end_time }}"></span></div>

            <div class="card-body">
                <p>{{ $seckill->title }}</p>
                <p>{{ $seckill->description }}</p>
                <p class="stock">剩余：{{ $seckill->quantity }} / {{ $seckill->stock }}</p>
                <p><del>{{ $seckill->ori_price }}</del> | <span class="kill-price">{{ $seckill->kill_price }}</span></p>
                <p>名称：{{ $goods->goods_name }}</p>
                <p>产地：{{ $goods->place }}</p>
                <p>品牌：{{ $goods->brand }}</p>
                <p>重量：{{ $goods->weight }}</p>
                <p>包装：{{ $goods->packing }}</p>
                <p>
                    @foreach(json_decode($seckill->pictures) as $picture)
                        <img height="200px" src="{{ $picture }}" alt="{{ $goods->title }}">
                    @endforeach
                </p>
                <form name="kill_form" data-action="#">
                    @csrf
                    <input type="hidden" name="id" value="{{ $seckill->id }}">
                    <p>抢购数量：<input type="number" class="form-controll" name="num"></p>
                    <p><button class="btn btn-danger" type="button" id="submit" disabled onclick="submit_order()">立即抢购</button></p>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function count_line(obj) {
            const begin_time = obj.getAttribute('data-begin'), end_time = obj.getAttribute('data-end');
            let time = count_dead_time(begin_time, end_time);
            obj.innerHTML = (time.time < 0?"距结束：" : "距开始：") + time.timeStr;
            if (time.time <= 0) {
                $('#submit').removeAttr('disabled');
            }

            if (time.time < 60*1000*2 && ($('form[name="kill_form"]').data('action') === '#')) {
                get_submit_url();
            }
        }
        window.onload = function() {
            const times = document.getElementsByClassName("begin_time");
            fixLocaltime().done(function(){
                Array.from(times).forEach(obj=>{
                    count_line(obj);
                    setInterval(()=>{
                        count_line(obj);
                    }, 1000);
                });
            });
        };

        function get_submit_url() {
            $.get('/seckill_url?skid={{$seckill->id}}', function(result) {
                $('form').data('action', result.url);
            });
        }

        function submit_order() {
            const url = $('form[name="kill_form"]').data('action');
            if (url === '#') {
                return;
            }

            $.ajax({
                url: url,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: document.kill_form.id.value,
                    num: document.kill_form.num.value,
                },
                success: function(result) {
                    window.location.href = "/seckill_order?no=" + result.order.order_no
                },
                error: function(err1, err2, err3, err4) {
                    console.log(err1, err2, err3, err4);
                }
            });
        }
    </script>
@endsection

