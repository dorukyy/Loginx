@extends('loginx::layouts.master')

@section('content')

    <div class="login-container">
        <h2>@lang('Mail Activation')</h2>
        @if($errors->get('loginx') !== null)
            @foreach ($errors->get('loginx') as $error)
                <div class="alert">
                    {!! $error !!}
                </div>
            @endforeach
        @endif
        @include('loginx::activation._form')

        <div class="login-link">
            <span>@lang('Already have an account?')</span>
            <a href="{{route('login.login-page')}}">@lang('Login')</a>
        </div>
        <div class="login-link">
            <span>@lang('New on our platform?')</span>
            <a href="{{route('register.register-page')}}">@lang('Create an account')</a>
        </div>


    </div>

@endsection

