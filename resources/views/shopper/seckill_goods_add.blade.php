@extends('layouts.app_shopper')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="goods_name" class="col-md-4 col-form-label text-md-right">{{ __('Goods Name') }}</label>

                            <div class="col-md-6">
                                <input id="goods_name" type="text" class="form-control{{ $errors->has('goods_name') ? ' is-invalid' : '' }}" name="goods_name" value="{{ old('goods_name') }}" required autofocus>

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
                                <input id="place" type="place" class="form-control{{ $errors->has('place') ? ' is-invalid' : '' }}" name="place" value="{{ old('place') }}" required>

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
                                <input id="brand" type="brand" class="form-control{{ $errors->has('brand') ? ' is-invalid' : '' }}" name="brand" required>

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
                                <input id="weight" type="number" class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" required>

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
                                <input id="packing" type="text" class="form-control{{ $errors->has('packing') ? ' is-invalid' : '' }}" name="packing" required>

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

                                <input id="detail_pictures" type="file" class="form-control{{ $errors->has('detail_pictures') ? ' is-invalid' : '' }}" name="detail_pictures[]" required>

                                @if ($errors->has('detail_pictures'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('detail_pictures') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
