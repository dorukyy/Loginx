<!DOCTYPE html>

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-security-policy" content="upgrade-insecure-requests"/>

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>@yield('title') {{ $title ?? config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}"/>


<link rel="stylesheet" href="{{ asset('css/loginx-style.css') }}"/><body>

@if(session()->has('success'))
    <div class="alert alert-success error-message">
        {{ session()->get('success') }}
    </div>
@endif

@yield('content')

@include('loginx::layouts.scripts')
@stack('scripts')

</body>

</html>


<style>

    .error-message {
        position: fixed !important;
        right: 1%;
        top: 1%;
        z-index: 9999;
        opacity: 1;
        transition: opacity 10s;
    }
</style>

<script>

    setTimeout(function () {
        $('.error-message').fadeOut('slow');
    }, 5000);
</script>
