@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header"><span class="begin_time" data-begin="{{ $flashSale->begin_time }}" data-end="{{ $flashSale->end_time }}"></span></div>

            <div class="card-body">
                <p>{{ $flashSale->title }}</p>
                <p>{{ $flashSale->description }}</p>
                <p class="stock">剩余：{{ $flashSale->quantity }} / {{ $flashSale->stock }}</p>
                <p><del>{{ $flashSale->ori_price }}</del> | <span class="kill-price">{{ $flashSale->kill_price }}</span></p>
                <p>名称：{{ $goods->goods_name }}</p>
                <p>产地：{{ $goods->place }}</p>
                <p>品牌：{{ $goods->brand }}</p>
                <p>重量：{{ $goods->weight }}</p>
                <p>包装：{{ $goods->packing }}</p>
                <p>
                    @foreach(json_decode($flashSale->pictures) as $picture)
                        <img height="200px" src="{{ $picture }}" alt="{{ $goods->title }}">
                    @endforeach
                </p>
                <form name="kill_form" data-action="#">
                    @csrf
                    <input type="hidden" name="id" value="{{ $flashSale->id }}">
                    <p>抢购数量：<input type="number" class="form-controll" name="num"></p>
                    <p><button class="btn btn-danger" type="button" id="submit" disabled onclick="submit_order()">立即抢购</button></p>
                </form>
            </div>
        </div>
    </div>
    <!-- Large modal -->
    <div class="modal fade" id="waiting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">抢购中</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    当前抢购人数过多，正在排队中，请等待...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function count_line(obj) {
            const begin_time = obj.getAttribute('data-begin'), end_time = obj.getAttribute('data-end');
            let time = tool.count_dead_time(begin_time, end_time);
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
            tool.fixLocaltime().done(function(){
                Array.from(times).forEach(obj=>{
                    count_line(obj);
                    setInterval(()=>{
                        count_line(obj);
                    }, 1000);
                });
            });
        };

        function get_submit_url() {
            $.get('/killUrl?skid={{$flashSale->id}}', function(result) {
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
                data: {
                    id: document.kill_form.id.value,
                    num: document.kill_form.num.value,
                },
                success: function() {
                    $('#waiting').modal('show');
                    checkOrderStatus();
                },
                error: function(err1, err2, err3, err4) {
                    console.log(err1, err2, err3, err4);
                }
            });
        }

        function checkOrderStatus() {
            $.ajax({
                url: '/orderStatus',
                data: {
                    id: document.kill_form.id.value,
                },
                success: function(result) {
                    if (result.code === 600000) {
                        $('#waiting').modal('hide');
                        alert('恭喜你抢到了！去支付吧');
                        window.location.href = '/orderFill?order_no=' + result.order_no
                    }
                    else if (result.code === 600001) {
                        $('#waiting').modal('hide');
                        alert('秒杀已结束！');
                    }
                    else {
                        setTimeout("checkOrderStatus()", 1000);
                    }
                },
                error: function(err1, err2, err3, err4) {
                    console.log(err1, err2, err3, err4);
                }
            });
        }
    </script>
@endsection

