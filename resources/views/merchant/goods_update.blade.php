@extends('layouts.app_merchant')
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('merchantGoodsList') }}">商品列表</a></li>
        <li class="active">商品修改</li>
    </ol>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('更新商品') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updateMerchantGoods') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $goods->id }}">
                        <div class="form-group row">
                            <label for="goods_name" class="col-md-4 col-form-label text-md-right">{{ __('Goods Name') }}</label>

                            <div class="col-md-6">
                                <input id="goods_name" type="text" class="form-control{{ $errors->has('goods_name') ? ' is-invalid' : '' }}" name="goods_name" value="{{ $goods->goods_name }}" required autofocus>

                                @if ($errors->has('goods_name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('goods_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="place" class="col-md-4 col-form-label text-md-right">{{ __('Place') }}</label>

                            <div class="col-md-6">
                                <input id="place" type="place" class="form-control{{ $errors->has('place') ? ' is-invalid' : '' }}" name="place" value="{{ $goods->place }}" required>

                                @if ($errors->has('place'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('place') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="brand" class="col-md-4 col-form-label text-md-right">{{ __('Brand') }}</label>

                            <div class="col-md-6">
                                <input id="brand" type="brand" class="form-control{{ $errors->has('brand') ? ' is-invalid' : '' }}" name="brand" value="{{ $goods->brand }}" required>

                                @if ($errors->has('brand'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('brand') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="weight" class="col-md-4 col-form-label text-md-right">{{ __('Weight') }}</label>

                            <div class="col-md-6">
                                <input id="weight" type="number" class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" value="{{ $goods->weight }}" required>

                                @if ($errors->has('weight'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('weight') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="packing" class="col-md-4 col-form-label text-md-right">{{ __('Packing') }}</label>

                            <div class="col-md-6">
                                <input id="packing" type="text" class="form-control{{ $errors->has('packing') ? ' is-invalid' : '' }}" name="packing" value="{{ $goods->packing }}" required>

                                @if ($errors->has('packing'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('packing') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="detail_pictures" class="col-md-4 col-form-label text-md-right">{{ __('Detail Pictures') }}</label>
                            <div class="col-md-6">
                                    @foreach(json_decode($goods->detail_pictures) as $picture)
                                        <div class="pic-wall">
                                            <input type="hidden" name="old_detail_pictures[]" value="{{ $picture }}">
                                            <img src="{{$picture}}" alt="{{ $goods->goods_name }}" width="120px">
                                            <span class="close">X</span>
                                        </div>
                                    @endforeach

                                    @if ($errors->has('detail_pictures'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('detail_pictures') }}</strong>
                                        </span>
                                    @endif
                                    <div id="detail_pictures">
                                        <button id="addPic" type="button" style="margin: 13px;" class="btn btn-dark">Add Picture</button>
                                        <div class="row" style="padding: 13px;">
                                            <input type="file" class="form-control{{ $errors->has('detail_pictures') ? ' is-invalid' : '' }}" name="detail_pictures[]">
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('保存修改') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('.pic-wall .close').click(function() {
                $(this).parents('.pic-wall').remove();
            });
            $('#addPic').click(function() {
                let new_pic = $(
                    `<div class="row" style="padding: 13px; position: relative">
                        <input type="file" class="form-control" name="detail_pictures[]" required>
                    </div>`);

                let remove = $(`<span class="remove">X</span>`).click(function() {
                    $(this).parent().remove();
                });

                new_pic.append(remove);
                $(this).before(new_pic)
            });
        });
    </script>
@endsection
