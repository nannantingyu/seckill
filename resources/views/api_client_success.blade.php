@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('api_client_id'))
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
            @else
                No message here
            @endif
        </div>
    </div>
</div>
@endsection
