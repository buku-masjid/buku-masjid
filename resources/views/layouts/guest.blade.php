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

    <title>@yield('title') - {{ Setting::get('masjid_name', config('masjid.name')) }}</title>

   <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    @yield('styles')
</head>
<body>
    <div class="d-none d-sm-block">
        <div class="nav-public d-flex align-items-center justify-content-between position-relative">
            <img src="{{ asset('images/logo_bukumasjid.svg') }}" style="width: 150px">
            <div class="position-relative text-center">
                <div class="nav-desktop position-relative shadow-sm rounded">
                    <ul class="nav">
                        <li class="nav-item px-2"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public_reports.index') }}">Laporan</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public.donate') }}">Infaq</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ route('public_schedules.index') }}">Kegiatan</a></li>
                        <li class="nav-item px-2"><a class="nav-link" href="{{ url('/kontak') }}">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="pe-3 pt-3 text-end" style="width: 150px"><a href="{{ route('login') }}" >Login</a></div>
        </div>
    </div>
    <div class="container p-0 bg-white">
        <div class="d-sm-none">
            <div class="row p-3">
                <div class="col text-start">
                    <img src="{{ asset('images/logo_bukumasjid.svg') }}" style="width: 100px">
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
                    <img src="{{ asset('images/logo_bm_shape.svg') }}" style="width: 100px">
                </div>
                <div class="mt-5 fs-2 sidebar-menu bd-highlight">
                    <ul>
                        <a href="{{ url('/') }}"><li class="pb-3">Beranda</li></a>
                        <a href="{{ route('public_reports.index') }}"><li class="pb-3">Laporan</li></a>
                        <a href="{{ route('public.donate') }}"><li class="pb-3">Infaq</li></a>
                        <a href="{{ route('public_schedules.index') }}"><li class="pb-3">Kegiatan</li></a>
                        <a href="{{ url('/kontak') }}"><li class="pb-3">Kontak</li></a>
                        <a href="{{ route('login') }}"><li class="py-3 border-top mt-5">Login</li></a>
                    </ul>
                </div>
                <div class="mt-auto bd-highlight">
                    <div>
                        <div class="p-10 cta-join">
                            Ingin kelola finansial masjid Anda ?
                            <span>Gabung ke BukuMasjid</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center">
                            <div class="pe-2">
                                <i class="ti">&#xf7e6;</i> bukumasjid
                            </div>
                            <div class="ps-2">
                                <i class="ti">&#xf7eb;</i> bukumasjid
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="pe-2">
                                <i class="ti">&#xec26;</i> bukumasjid
                            </div>
                            <div class="ps-2">
                                <i class="ti">&#xec20;</i> bukumasjid
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-center">
                            (c) 2024 Bukumasjid
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="offset-0 offset-lg-1 offset-xl-2 col-12 col-lg-10 col-xl-8">
                    <div class="py-1">
                        <nav class="nav d-flex justify-content-between">
                            <a class="py-2 px-1 {{ in_array(Request::segment(1), [null]) ? 'text-primary strong' : 'text-dark' }}" href="{{ url('/') }}">
                                <i class="fe fe-home"></i> {{ __('app.home') }}
                            </a>
                            <a class="py-2 px-1 {{ in_array(Request::segment(1), ['laporan-kas']) ? 'text-primary strong' : 'text-dark' }}" href="{{ route('public_reports.index') }}">
                                <i class="fe fe-layout"></i> {{ __('report.report') }}
                            </a>
                            <a class="py-2 px-1 {{ in_array(Request::segment(1), ['donasi']) ? 'text-primary strong' : 'text-dark' }}" href="{{ route('public.donate') }}">
                                <i class="fe fe-pocket"></i> {{ __('app.donate') }}
                            </a>
                            @if (Route::has('public_schedules.index'))
                                <a class="py-2 px-1 {{ in_array(Request::segment(1), ['jadwal']) ? 'text-primary strong' : 'text-dark' }}" href="{{ route('public_schedules.index') }}">
                                    <i class="fe fe-calendar"></i> {{ __('lecturing.public_schedule') }}
                                </a>
                            @endif
                            @auth
                            <a class="py-2 px-1 text-dark" href="{{ route('home') }}"><i class="fe fe-user"></i> {{ auth()->user()->name }}</a>
                            @else
                            <a class="py-2 px-1 text-dark" href="{{ route('login') }}"><i class="fe fe-user"></i> {{ __('auth.login') }}</a>
                            @endauth
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    @yield('content')
    <!-- <main role="main" class="container-fluid px-0">
        <div class="row">
            <div class="offset-0 offset-lg-1 offset-xl-2 col-12 col-lg-10 col-xl-8">

            </div>
        </div>
    </main> -->
    <script src="{{ asset('js/app.js') }}" ></script>
    @include('layouts.partials.noty')
    @stack('scripts')
</body>
</html>
