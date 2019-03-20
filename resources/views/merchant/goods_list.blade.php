@extends('layouts.app_merchant')
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('merchantGoodsList') }}">首页</a></li>
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
            </table>
        </div>
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('merchantGoodsAdd' )}}">添加商品</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $.ajax({
            url: "{{ route('merchantGoods') }}",
            dataType: "json",
            success: result => {
                let tbody = '';
                for(let row of result) {
                    tbody += `<tr>
                        <td><a href="{{ route('merchantGoodsUpdate') }}?id=${row.goods_id}">${row.goods_name}</a></td>
                        <td>${row.place}</td>
                        <td>${row.weight}</td>
                        <td>${row.packing}</td>
                        <td>${row.brand}</td>
                        <td>
                        `;

                    let pictures = JSON.parse(row.detail_pictures);
                    for (let picture of pictures) {
                        tbody += `<img width="100px" src="${picture}" alt="${row.goods_name}">`;
                    }

                    tbody += `</td>
                        <td>
                            <a href="{{ route('merchantGoodsDel') }}?id=${row.goods_id}">删除</a>
                            <a href="{{ route('merchantFlashSaleApply') }}?id=${row.goods_id}">申请秒杀</a>
                        </td>
                        </tr>`
                }

                $('table').append(tbody);
            }
        });
    </script>
@endsection
