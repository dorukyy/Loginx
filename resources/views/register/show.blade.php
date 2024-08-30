@extends('loginx::layouts.master')

@section('content')
<div class="login-container">
    <h2>Register</h2>
    @if($errors->get('loginx') !== null)
            @foreach ($errors->get('loginx') as $error)
                <div class="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif
    @include('loginx::register._form')
    <div class="login-link">
        <span>Already have an account?</span>
        <a href="{{route('login.login-page')}}">Login</a>
    </div>
</div>

@endsection

