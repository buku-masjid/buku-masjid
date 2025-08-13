<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
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

    <title>@yield('title') - {{ Setting::get('masjid_name', config('masjid.name')) }}</title>
    <meta property="og:title" content="@yield('title') - {{ Setting::get('masjid_name', config('masjid.name')) }}" />

    <!-- Styles -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    @yield('head_tags')
    @yield('styles')
</head>
<body>
    <div class="d-none d-sm-block">
        <div class="nav-public d-flex align-items-center justify-content-between position-relative">
            <a href="{{ url('/') }}"><img src="{{ asset('images/logo_bukumasjid.svg') }}" style="width: 150px"></a>
            <div class="position-relative text-center">
                <div class="nav-desktop position-relative shadow-sm rounded">
                    <ul class="nav">
                        <li class="nav-item px-2"><a class="nav-link" href="{{ url('/') }}">{{ __('app.home') }}</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public_reports.index') }}">{{ __('report.report') }}</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public.books.index') }}">{{ __('app.program') }}</a></li>
                        @if (Route::has('public_schedules.index'))
                            <li class="nav-item px-2"><a class="nav-link" href="{{ route('public_schedules.this_week') }}">{{ __('lecturing.public_schedule') }}</a></li>
                        @endif
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public.contact') }}">{{ __('app.contact') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-end" style="width: 150px">
                @if (auth()->check())
                    <a href="{{ route('home') }}" >{{ auth()->user()->name }}</a>
                @else
                    <a href="{{ route('login') }}" >{{ __('auth.login') }}</a>
                @endif
            </div>
        </div>
    </div>
    <div class="container p-0 bg-white">
        <div class="d-sm-none">
            <div class="row p-3 me-0">
                <div class="col text-start">
                    <a href="{{ url('/') }}"><img src="{{ asset('images/logo_bukumasjid.svg') }}" style="width: 100px"></a>
                </div>
                <div class="col text-end">
                    <a class="btn position-relative z-2" data-bs-toggle="offcanvas" href="#offcanvasStart" role="button" aria-controls="offcanvasStart">
                        <i class="ti ti-baseline-density-medium"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel" aria-modal="true" role="dialog">
            <div class="text-end p-3">
                <button type="button" class="btn-close text-reset float-end" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-0 d-flex align-items-stretch flex-column bd-highlight">
                <div class="text-center bd-highlight">
                    <a href="{{ url('/') }}"><img src="{{ asset('images/logo_bm_shape.svg') }}" style="width: 80px"></a>
                </div>
                <div class="mt-3 fs-2 sidebar-menu bd-highlight">
                    <ul>
                        <li class="py-3 border-top mt-2"><a href="{{ url('/') }}">{{ __('app.home') }}</a></li>
                        <li class="pb-3"><a href="{{ route('public_reports.index') }}">{{ __('report.report') }}</a></li>
                        <li class="pb-3"><a href="{{ route('public.books.index') }}">{{ __('app.program') }}</a></li>
                        @if (Route::has('public_schedules.index'))
                            <li class="pb-3"><a href="{{ route('public_schedules.this_week') }}">{{ __('lecturing.public_schedule') }}</a></li>
                        @endif
                        <li class="pb-3"><a href="{{ route('public.contact') }}">{{ __('app.contact') }}</a></li>
                        <li class="py-3 border-top mt-2">
                            @if (auth()->check())
                                <a href="{{ route('home') }}" >{{ auth()->user()->name }}</a>
                            @else
                                <a href="{{ route('login') }}" >{{ __('auth.login') }}</a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="mt-auto bd-highlight">
                    <div>
                        @include('layouts.public._cta_join')
                    </div>
                    <div class="mt-4 text-center">
                        @include('layouts.public._footer_links')
                    </div>
                    <div class="mt-4">
                        <div class="text-center">
                            @include('layouts.public._copyright')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')
    @include('layouts._public_footer')
    <script src="{{ asset('js/app.js') }}" ></script>
    @include('layouts.partials.noty')
    @stack('scripts')
</body>
</html>
