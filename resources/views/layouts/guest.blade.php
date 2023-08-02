<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.6/examples/blog/blog.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="blog-header py-3 text-center">
            <a class="blog-header-logo text-dark" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        </header>

        <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between">
                <a class="p-2" href="{{ url('/') }}"><i class="fe fe-home"></i></a>
                <a class="p-2" href="{{ route('public_reports.index') }}">{{ __('report.view_report') }}</a>
                <a class="p-2" href="{{ route('login') }}">{{ __('auth.login') }}</a>
            </nav>
        </div>
    </div>
    <main role="main" class="container">
        @yield('content')
    </main>
    <script src="{{ asset('js/app.js') }}" ></script>
    @include('layouts.partials.noty')
</body>
</html>
