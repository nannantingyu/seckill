@extends('layouts.app_shopper')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register Api Client') }}</div>

                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div>您的app_client_id:</div><div>{{session('api_client_id')}}</div>
                        </li>
                        <li class="list-group-item">
                            <div>您的app_client_name:</div><div>{{session('api_client_name')}}</div>
                        </li>
                        <li class="list-group-item">
                            <div>您的app_secret_secret:</div><div>{{session('api_client_secret')}}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
