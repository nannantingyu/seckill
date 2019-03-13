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
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">{{ __('Account Name') }}</label>

                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control{{ $errors->has('account_name') ? ' is-invalid' : '' }}" name="account_name" value="{{ old('account_name') }}" required autofocus>

                                @if ($errors->has('account_name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('account_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="avatar" class="col-md-4 col-form-label text-md-right">{{ __('Avatar') }}</label>

                            <div class="col-md-6">

                                <input id="avatar" type="file" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : '' }}" name="avatar" required>

                                @if ($errors->has('avatar'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('avatar') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nick_name" class="col-md-4 col-form-label text-md-right">{{ __('Nick Name') }}</label>

                            <div class="col-md-6">
                                <input id="nick_name" type="text" class="form-control{{ $errors->has('nick_name') ? ' is-invalid' : '' }}" name="nick_name" value="{{ old('nick_name') }}" required>

                                @if ($errors->has('nick_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nick_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="scope" class="col-md-4 col-form-label text-md-right">{{ __('Scope') }}</label>

                            <div class="col-md-6">
                                <input id="scope" type="text" class="form-control{{ $errors->has('scope') ? ' is-invalid' : '' }}" name="scope" value="{{ old('scope') }}" required>

                                @if ($errors->has('scope'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('scope') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
