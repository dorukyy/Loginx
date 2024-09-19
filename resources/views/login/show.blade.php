@extends('loginx::layouts.master')

@section('content')

    <div class="login-container">
        <h2>Login</h2>
        @if($errors->get('loginx') !== null)
            @foreach ($errors->get('loginx') as $error)
                <div class="alert">
                    {!! $error !!}
                </div>
            @endforeach
        @endif
        @include('loginx::login._form')



    <div id="g_id_onload"
         data-client_id="1039930373728-d01b4su84vmrgh0j4e0lpr4e6p1hquq5.apps.googleusercontent.com"
         data-callback="handleCredentialResponse">
    </div>
    <div class="g_id_signin" data-type="standard"></div>

@endsection

