@extends('layouts.app_shopper')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/seckill_goods_list">首页</a></li>
        <li><a href="/seckill_list">秒杀列表</a></li>
        <li class="active">秒杀修改</li>
    </ol>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Goods') }}</div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $goods->id }}">
                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('秒杀标题') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ $goods->title }}" required autofocus>

                                @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('秒杀详情') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ $goods->description }}" required>

                                @if ($errors->has('description'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="ori_price" class="col-md-4 col-form-label text-md-right">{{ __('原价') }}</label>

                            <div class="col-md-6">
                                <input id="ori_price" type="number" class="form-control{{ $errors->has('ori_price') ? ' is-invalid' : '' }}" name="ori_price" value="{{ $goods->ori_price }}" required>

                                @if ($errors->has('ori_price'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ori_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kill_price" class="col-md-4 col-form-label text-md-right">{{ __('kill_price') }}</label>

                            <div class="col-md-6">
                                <input id="kill_price" type="number" class="form-control{{ $errors->has('kill_price') ? ' is-invalid' : '' }}" name="kill_price" value="{{ $goods->kill_price }}" required>

                                @if ($errors->has('kill_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kill_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="begin_time" class="col-md-4 col-form-label text-md-right">{{ __('begin_time') }}</label>

                            <div class="col-md-6">
                                <input id="begin_time" type="text" class="form-control{{ $errors->has('begin_time') ? ' is-invalid' : '' }}" name="begin_time" value="{{ $goods->begin_time }}" required>

                                @if ($errors->has('begin_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('begin_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="end_time" class="col-md-4 col-form-label text-md-right">{{ __('end_time') }}</label>

                            <div class="col-md-6">
                                <input id="end_time" type="text" class="form-control{{ $errors->has('end_time') ? ' is-invalid' : '' }}" name="end_time" value="{{ $goods->end_time }}" required>

                                @if ($errors->has('end_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('quantity') }}</label>

                            <div class="col-md-6">
                                <input id="quantity" type="number" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="quantity" value="{{ $goods->quantity }}" required>

                                @if ($errors->has('quantity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock" class="col-md-4 col-form-label text-md-right">{{ __('stock') }}</label>

                            <div class="col-md-6">
                                <input id="stock" type="number" class="form-control{{ $errors->has('stock') ? ' is-invalid' : '' }}" name="stock" value="{{ $goods->stock }}" required>

                                @if ($errors->has('stock'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('stock') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pictures" class="col-md-4 col-form-label text-md-right">{{ __('秒杀图片') }}</label>
                            <div class="col-md-6">
                                <div class="row">
                                    @foreach(json_decode($goods->pictures) as $picture)
                                        <div class="pic-wall">
                                            <input type="hidden" name="old_pictures[]" value="{{ $picture }}">
                                            <img src="{{$picture}}" alt="{{ $goods->title }}" width="120px">
                                            <span class="close">X</span>
                                        </div>
                                    @endforeach

                                    @if ($errors->has('pictures'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('pictures') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="row">
                                        <div id="pictures">
                                            <button id="addPic" type="button" style="margin: 13px;" class="btn btn-dark">添加图片</button>
                                            <div class="row" style="padding: 13px;">
                                                <input type="file" class="form-control{{ $errors->has('pictures') ? ' is-invalid' : '' }}" name="pictures[]">
                                            </div>
                                        </div>
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
                let new_pic = $(`<div class="row" style="padding: 13px; position: relative">
                                    <input type="file" class="form-control" name="pictures[]" required>
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
