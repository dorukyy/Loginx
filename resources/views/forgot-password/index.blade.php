@extends('loginx::layouts.master')

@section('content')

    <div class="login-container">
        <h2>@lang('Forgot Password')</h2>
        @if(isset($errors))
            @foreach ($errors->getBag('default')->all() as $error)
                <div class="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif
        @include('loginx::forgot-password._form')

        <div class="login-link">
            <span>@lang('Already have an account?')</span>
            <a href="{{route('login')}}">@lang('Login')</a>
        </div>
        <div class="login-link">
            <span>@lang('New on our platform?')</span>
            <a href="{{route('register')}}">@lang('Create an account')</a>
        </div>


    </div>

@endsection

