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

    <title>@yield('title') - {{ Setting::get('masjid_name', config('masjid.name')) }}</title>
    <meta property="og:title" content="@yield('title') - {{ Setting::get('masjid_name', config('masjid.name')) }}" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/css/tabler.min.css" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    @php $theme = env('JAMMASJID_THEME', 'default'); @endphp
    <link href="{{ asset('css/jammasjid/' . $theme . '/style.css') }}" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    @yield('head_tags')
    @yield('styles')
</head>
<body class="bm-bg-jammasjid p-4 relative">
    <!-- Prayer Interlude Modal -->
    <div id="prayerInterludeModal" class="prayer-modal">
        <div class="prayer-message">
            <h2>Menuju Iqomah</h2>
            <div id="interludeCountdown" class="countdown-timer">00:00</div>
            <img src="{{ asset('images/bm_logo_white.svg') }}" alt="Prayer Image" class="position-absolute top-0 end-0 me-5 mt-4">
        </div>
    </div>
    <div id="prayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="margin-bottom: 100px;">
            <h1>Sholat Sedang Berlangsung</h1>
            <img src="{{ asset('images/bm_logo_white.svg') }}" alt="Prayer Image" class="position-absolute top-0 end-0 me-5 mt-4">
        </div>
    </div>
    <div id="fridayPrayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="margin-bottom: 100px;">
            <h2 style="line-height: 1;">Ibadah<br>Sholat Jumat<br>Sedang Berlangsung</h2>
            <img src="{{ asset('images/bm_logo_white.svg') }}" alt="Prayer Image" class="position-absolute top-0 end-0 me-5 mt-4">
        </div>
    </div>
    @yield('content')
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/js/tabler.min.js"></script>
    @stack('scripts')
     <script>
         window.prayerStartIn = {{ config('jam-masjid.prayer_start_in') }};
         window.prayerEndIn = {{ config('jam-masjid.prayer_end_in') }};
         window.nextPrayerName = nextShalatTime;
         console.log(nextShalatTime);
         window.fridayPrayerEndIn = {{ config('jam-masjid.friday_end_in') }};
     </script>
     <script src="{{ asset('js/jammasjid/prayer-modal.js') }}"></script>
     <script src="{{ asset('js/jammasjid/kiosk-mode.js') }}"></script>
 </body>
</html>
