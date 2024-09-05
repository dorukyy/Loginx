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

        <div class="register-link">
            <span>@lang('New on our platform?')</span>
            <a href="{{route('register.register-page')}}">@lang('Create an account')</a>
        </div>

    </div>

@endsection

