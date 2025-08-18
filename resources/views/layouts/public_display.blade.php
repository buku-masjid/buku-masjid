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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {}
            }
        }
    </script>
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
<body class="bm-bg-jammasjid p-3 2xl:p-4 relative">
    <!-- Prayer Interlude Modal -->
    <div id="iqamahIntervalModal" class="prayer-modal">
        <div class="prayer-message">
            <h2>{{ __('shalat_time.iqamah_interval_text') }}</h2>
            <div id="iqamahIntervalCountdown" class="countdown-timer">00:00</div>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="shalatModal" class="prayer-modal">
        <div class="prayer-message">
            <img class="mx-auto my-10" src="{{ Storage::url(Setting::get('masjid_logo_path')) }}">
            <h2 style="line-height: 1;">{!! __('shalat_time.shalat_interval_text') !!}</h2>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="fridayModal" class="prayer-modal">
        <div class="prayer-message">
            <img class="mx-auto my-10" src="{{ Storage::url(Setting::get('masjid_logo_path')) }}">
            <h2 style="line-height: 1;">{!! __('shalat_time.friday_interval_text') !!}</h2>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    @yield('content')
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        const cacheKey = `shalat_times_{{ now()->format('Ymd') }}`;
        const cachedData = localStorage.getItem(cacheKey);
        const shalatDailySchedule = JSON.parse('{!! json_encode(__("shalat_time.daily_schedules")) !!}')
        let shalatTimeData = "";
        let nextShalatTime = 'imsak';
        window.iqamahIntervalInMinutes = {!! json_encode(config('public_display.iqamah_interval_in_minutes')) !!};
        window.shalatIntervalInMinutes = {!! json_encode(config('public_display.shalat_interval_in_minutes')) !!};
        const audio = new Audio("{{ asset('audio/beep.mp3') }}");
        if (cachedData) {
            shalatTimeData = JSON.parse(cachedData);
        } else {
            fetch("{{ route('api.public_shalat_time.show') }}")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error:", data.error);
                } else {
                    shalatTimeData = data;
                    localStorage.setItem(cacheKey, JSON.stringify(shalatTimeData));
                }
            });
        }
        // shalatTimeData.schedules.dzuhr = '11:56';
    </script>
    <script src="{{ asset('js/public_display/next-shalat-counter.js') }}"></script>
    <script src="{{ asset('js/public_display/iqamah-shalat-modal.js') }}"></script>
    <script src="{{ asset('js/public_display/time-calculator.js') }}"></script>
    <script src="{{ asset('js/public_display/kiosk-mode.js') }}"></script>
</body>
</html>
