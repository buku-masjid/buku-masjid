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
    <meta http-equiv="refresh" content="600"> <!-- Refresh every 10 minutes -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Setting::get('masjid_name', config('masjid.name')) }}</title>
    <meta property="og:title" content="{{ Setting::get('masjid_name', config('masjid.name')) }}" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/css/tabler.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    <link href="{{ asset("css/public_display/$theme/style.css") }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @yield('head_tags')
    @yield('styles')
    <style>
        html, body {
            height: 100%; /* Make sure the html and body take up the full viewport height */
            margin: 0;  /* Remove default body margins */
            display: flex; /* Enable flexbox for the body */
            flex-direction: column; /* Stack children vertically */
            margin-bottom:0 !important;
        }
    </style>
</head>
<body class="bm-bg-jammasjid p-4 relative">
    <!-- Prayer Interlude Modal -->
    <div id="prayerInterludeModal" class="prayer-modal">
        <div class="prayer-message">
            <h2>Menuju Iqomah</h2>
            <div id="interludeCountdown" class="countdown-timer">00:00</div>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="prayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" style="margin-bottom: 100px;">
            <h1>Sholat Sedang Berlangsung</h1>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="fridayPrayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" style="margin-bottom: 100px;">
            <h2 style="line-height: 1;">Ibadah<br>Sholat Jumat<br>Sedang Berlangsung</h2>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    @yield('content')
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/js/tabler.min.js"></script>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        window.prayerStartIn = {{ config('public_display.prayer_start_in') }};
        window.prayerEndIn = {{ config('public_display.prayer_end_in') }};
        window.nextPrayerName = nextShalatTime;
        window.fridayPrayerEndIn = {{ config('public_display.friday_end_in') }};
    </script>
    <script src="{{ asset('js/public_display/time-calculator.js') }}"></script>
    <script src="{{ asset('js/public_display/prayer-modal.js') }}"></script>
    <script src="{{ asset('js/public_display/kiosk-mode.js') }}"></script>
 </body>
</html>
