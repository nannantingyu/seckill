@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="section">
            <p class="section-title">最新秒杀</p>
            <table class="table">
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $.ajax({
                url: '{{ route('flashSaleList') }}',
                dataType: 'json',
                success: result=> {
                    render(result);
                }
            });
        });

        function render(data) {
            let table_body = "";
            data.forEach(row=> {
                let pictures = JSON.parse(row.pictures);
                table_body += `<tr>
                <td><img width="100" src="${pictures[0]}" alt="${row.title}"></td>
                <td>${row.title}</td>
                <td>${row.ori_price}</td>
                <td>${row.kill_price}</td>
                <td><a href="/shopper_info?id=${row.merchant_id}">${row.nick_name}</a></td>
                <td class="begin_time" data-begin="${row.begin_time}" data-end="${row.end_time}"></td>
                <td><a href="${row.url}">购买</a></td></tr>
                `;
            });

            $('table').html(table_body);
            countDown();
        }

        function countDown() {
            const times = document.getElementsByClassName("begin_time");
            Array.from(times).forEach(obj=>{
                const begin_time = obj.getAttribute('data-begin'), end_time = obj.getAttribute('data-end');
                let time = tool.count_dead_time(begin_time, end_time);
                obj.innerHTML = (time.time < 0?"距结束：" : "距开始：") + time.timeStr;
                setInterval(()=>{
                    let time = tool.count_dead_time(begin_time, end_time);
                    obj.innerHTML = (time.time < 0?"距结束：" : "距开始：") + time.timeStr;
                }, 1000);
            });
        }
    </script>
@endsection
