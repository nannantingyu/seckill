@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <p>该秒杀商品不存在或者已过期！</p>
        <p id="time">5</p>
    </div>
@endsection
@section('script')
    <script>
        setInterval(()=>{
            const t = document.getElementById('time');
            t.innerHTML = parseInt(t.innerHTML) - 1;
            if (t.innerHTML <= 0) {
                window.location.href = "/";
            }
        }, 1000)
    </script>
@endsection
