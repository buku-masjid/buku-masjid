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

    <title>@yield('title') - {{ config('masjid.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
</head>
<body>
    <div class="container-fluid">
        <header class="py-5 text-center">
            <a class="h1 text-dark" href="{{ url('/') }}">{{ config('masjid.name') }}</a>
        </header>
    </div>
    <div class="navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="offset-0 offset-lg-1 offset-xl-2 col-12 col-lg-10 col-xl-8">
                    <div class="py-1">
                        <nav class="nav d-flex justify-content-between">
                            <a class="p-2 {{ in_array(Request::segment(1), [null]) ? 'text-primary strong' : 'text-dark' }}" href="{{ url('/') }}">
                                <i class="fe fe-home"></i> {{ __('app.home') }}
                            </a>
                            <a class="p-2 {{ in_array(Request::segment(1), ['laporan-kas']) ? 'text-primary strong' : 'text-dark' }}" href="{{ route('public_reports.index') }}">
                                <i class="fe fe-layout"></i> {{ __('report.report') }}
                            </a>
                            <a class="p-2 {{ in_array(Request::segment(1), ['jadwal']) ? 'text-primary strong' : 'text-dark' }}" href="{{ route('public_schedules.index') }}">
                                <i class="fe fe-calendar"></i> {{ __('lecturing_schedule.public_schedule') }}
                            </a>
                            @auth
                            <a class="p-2 text-dark" href="{{ route('home') }}"><i class="fe fe-user"></i> {{ auth()->user()->name }}</a>
                            @else
                            <a class="p-2 text-dark" href="{{ route('login') }}"><i class="fe fe-user"></i> {{ __('auth.login') }}</a>
                            @endauth
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <main role="main" class="container-fluid">
        <div class="row">
            <div class="offset-0 offset-lg-1 offset-xl-2 col-12 col-lg-10 col-xl-8">
                @yield('content')
            </div>
        </div>
    </main>
    <script src="{{ asset('js/app.js') }}" ></script>
    @include('layouts.partials.noty')
</body>
</html>
