@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <table class="table">
            <tbody id="table_body"></tbody>
        </table>
        <div class="section">
            <p class="section-title">最新秒杀</p>
            <ul class="goods-list">
                <li>
                    <img src="" alt="">
                    <p class="goods_name"><a href="/kill?id=">title</a> </p>
                    <p class="ori_price">原价：¥ori_price</p>
                    <p class="kill_price">秒杀价：¥kill_price</p>
                    <p class="shopper_name">店铺：<a href="/shopper_info?id=merchant_id">nick_name</a> </p>
                    <p class="begin_time" data-begin="begin_time" data-end="end_time"></p>
                    <p><a href="/">立即秒杀</a></p>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $.ajax({
                url: '',
                data: {},
                dataType: 'json',
                success: result=> {
                    render(result);
                }
            });
        });

        function render(data) {
            let table_body = "";
            data.forEach(row=> {
                table_body += ``;
            });

            $('#table_body').html(table_body);
            countDown();
        }

        function countDown() {
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
