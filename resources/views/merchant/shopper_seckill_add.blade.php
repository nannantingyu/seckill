@extends('layouts.app_shopper')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('seckill_add') }}" enctype="multipart/form-data">
                        @csrf
                        <input id="goods_id" type="hidden"  name="goods_id" value="{{ $goods->goods_id }}">

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('商品标题') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') ?? $goods->goods_name }}" required autofocus>

                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('商品描述') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" required autofocus>

                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ori_price" class="col-md-4 col-form-label text-md-right">{{ __('原始价格') }}</label>

                            <div class="col-md-6">
                                <input id="ori_price" type="text" class="form-control{{ $errors->has('ori_price') ? ' is-invalid' : '' }}" name="ori_price" value="{{ old('ori_price') }}" required autofocus>

                                @if ($errors->has('ori_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ori_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kill_price" class="col-md-4 col-form-label text-md-right">{{ __('秒杀价格') }}</label>

                            <div class="col-md-6">
                                <input id="kill_price" type="text" class="form-control{{ $errors->has('kill_price') ? ' is-invalid' : '' }}" name="kill_price" value="{{ old('kill_price') }}" required autofocus>

                                @if ($errors->has('kill_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kill_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="begin_time" class="col-md-4 col-form-label text-md-right">{{ __('开始时间') }}</label>

                            <div class="col-md-6">
                                <input id="begin_time" type="text" class="form-control{{ $errors->has('begin_time') ? ' is-invalid' : '' }}" name="begin_time" value="{{ old('begin_time') }}" required autofocus>

                                @if ($errors->has('begin_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('begin_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="end_time" class="col-md-4 col-form-label text-md-right">{{ __('结束时间') }}</label>

                            <div class="col-md-6">
                                <input id="end_time" type="text" class="form-control{{ $errors->has('end_time') ? ' is-invalid' : '' }}" name="end_time" value="{{ old('end_time') }}" required autofocus>

                                @if ($errors->has('end_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('数量') }}</label>

                            <div class="col-md-6">
                                <input id="quantity" type="quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="quantity" value="{{ old('quantity') }}" required>

                                @if ($errors->has('quantity'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock" class="col-md-4 col-form-label text-md-right">{{ __('库存') }}</label>

                            <div class="col-md-6">
                                <input id="stock" type="stock" class="form-control{{ $errors->has('stock') ? ' is-invalid' : '' }}" value="{{ old('stock') }}" name="stock" required>

                                @if ($errors->has('stock'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('stock') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pictures" class="col-md-4 col-form-label text-md-right">{{ __('商品图片') }}</label>

                            <div class="col-md-6">

                                <input id="pictures" type="file" class="form-control{{ $errors->has('pictures') ? ' is-invalid' : '' }}" name="pictures[]" required>

                                @if ($errors->has('pictures'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pictures') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('添加秒杀') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
