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
    <div id="prayerInterludeModal" class="prayer-modal">
        <div class="prayer-message">
            <h2>{{ __('shalat_time.iqamah_interval_text') }}</h2>
            <div id="interludeCountdown" class="countdown-timer">00:00</div>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="prayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img class="d-inline" src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" style="margin-bottom: 100px;">
            <h2 style="line-height: 1;">{!! __('shalat_time.shalat_interval_text') !!}</h2>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    <div id="fridayPrayerModal" class="prayer-modal">
        <div class="prayer-message">
            <img class="d-inline" src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" style="margin-bottom: 100px;">
            <h2 style="line-height: 1;">{!! __('shalat_time.friday_interval_text') !!}</h2>
            @yield('bukumasjid_logo_image')
        </div>
    </div>
    @yield('content')
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/js/tabler.min.js"></script>
    @stack('scripts')


    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        // Prayer Modal Iqumah Logic
        document.addEventListener('DOMContentLoaded', function() {
            const cacheKey = `shalat_times_{{ now()->format('Ymd') }}`;
            const cachedData = localStorage.getItem(cacheKey);
            const shalatDailySchedule = JSON.parse('{!! json_encode(__("shalat_time.daily_schedules")) !!}')
            let shalatTimeData = "";
            let nextShalatTime = 'imsak';
            window.prayerStartIn = {{ config('public_display.prayer_start_in') }};
            {{-- window.prayerStartIn = {!! json_encode(config('public_display.iqamah_interval_in_minutes')) !!}; --}}
            window.prayerEndIn = {{ config('public_display.prayer_end_in') }};
            window.nextPrayerName = nextShalatTime;
            window.fridayPrayerEndIn = {{ config('public_display.friday_end_in') }};
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
            shalatTimeData.schedules.ashr = '15:03';

            function updateTimeInfoNextShalat() {
                updateElementsContent(shalatTimeData);
            }

            function updateElementsContent(shalatTimeData) {
                document.querySelectorAll("[data-time]").forEach((element) => {
                    element.textContent = shalatTimeData.schedules[element.dataset.time];
                });

                const now = new Date();
                const currentMinutes = now.getHours() * 60 + now.getMinutes();
                const currentSeconds = 59 - now.getSeconds() ;

                for (let prop in shalatTimeData.schedules) {
                    const value = shalatTimeData.schedules[prop];
                    if (value.match(/^\d{2,}:\d{2}$/)) {
                        const [hour, minute] = value.split(":").map(Number);
                        if (hour * 60 + minute > currentMinutes) {
                            nextShalatTime = prop;
                            break;
                        }
                    }
                }

                document.getElementById('timeID').textContent = shalatDailySchedule[nextShalatTime];

                const elements = document.getElementsByClassName("jm-card");
                for (let i = 0; i < elements.length; i++) {
                    elements[i].classList.remove("jm-card-active");
                }

                const element = document.getElementById(nextShalatTime);
                element.classList.add("jm-card-active");

                const [nextHour, nextMinute] = shalatTimeData.schedules[nextShalatTime].split(":").map(Number);
                const nextMinutes = nextHour * 60 + nextMinute;

                let remainingMinutes = nextMinutes - currentMinutes;
                if (remainingMinutes < 0) {
                    remainingMinutes += 24 * 60;
                }

                const hoursLeft = Math.floor(remainingMinutes / 60);
                const minutesLeft = (remainingMinutes % 60) - 1;

                function addLeadingZero(number) {
                    return (number < 10 ? '0' : '') + number;
                }
                //let currentSeconds = new Date().getSeconds();

                document.getElementById('timeRemaining').textContent = addLeadingZero(hoursLeft) + " : " + addLeadingZero(minutesLeft) + " : " + addLeadingZero(currentSeconds); // Get seconds here!
            }

            setInterval(updateTimeInfoNextShalat, 1000);

            const prayerModal = document.getElementById('prayerModal');
            const prayerInterludeModal = document.getElementById('prayerInterludeModal');
            const fridayPrayerModal = document.getElementById('fridayPrayerModal');
            const interludeCountdown = document.getElementById('interludeCountdown');
            const timeRemainingElement = document.getElementById('timeRemaining');
            let startTimeout, endTimeout, countdownInterval;

            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();
            const currentSeconds = 60 - now.getSeconds() ;

            for (let prop in shalatTimeData.schedules) {
                const value = shalatTimeData.schedules[prop];
                if (value.match(/^\d{2,}:\d{2}$/)) {
                    const [hour, minute] = value.split(":").map(Number);
                    if (hour * 60 + minute > currentMinutes) {
                        nextShalatTime = prop;
                        break;
                    }
                }
            }

            // Clear any existing intervals/timeouts on page load
            if (countdownInterval) clearInterval(countdownInterval);
            if (startTimeout) clearTimeout(startTimeout);
            if (endTimeout) clearTimeout(endTimeout);

            // Toggle modal visibility
            function toggleModal() {
                if (prayerModal) {
                    prayerModal.classList.toggle('show');
                }
            }

            // Handle spacebar press
            document.addEventListener('keydown', function(event) {
                if (event.code === 'Space' && window.location.pathname === '/display') {
                    event.preventDefault();
                    toggleModal();
                }
            });

            // Check countdown timer
            function checkCountdown() {
                // console.log('start');
                if (timeRemainingElement) {
                    // Remove spaces and check both formats
                    const timeText = timeRemainingElement.textContent.replace(/\s+/g, '');
                    if (timeText === '00:00:03' || timeText === '00:03') {
                        audio.play();
                    }

                    if (timeText === '00:00:00' || timeText === '00:00') {
                        // Clear any existing timeouts
                        if (startTimeout) clearTimeout(startTimeout);
                        if (endTimeout) clearTimeout(endTimeout);

                        // --- SPECIAL CASE: Friday Ashar ---
                        const today = new Date();
                        const isFriday = today.getDay() === 1;
                        const nextPrayer = (window.nextPrayerName || '').toLowerCase();

                        // console.log('isFriday:', isFriday);
                        // console.log('nextPrayer:', nextPrayer);

                        if (isFriday && nextPrayer === 'ashar') {
                            // Show Friday modal directly, skip interlude
                            if (fridayPrayerModal) {
                                fridayPrayerModal.classList.add('show');
                                endTimeout = setTimeout(() => {
                                    fridayPrayerModal.classList.remove('show');
                                }, (window.fridayPrayerEndIn || 10) * 60 * 1000); // default 10 min if not set
                            }
                            return; // Stop further execution
                        }

                        // Show interlude modal with countdown
                        if (prayerInterludeModal) {
                            prayerInterludeModal.classList.add('show');
                            let countdown = window.prayerStartIn * 60; // Convert to seconds

                            // Update countdown every second
                            countdownInterval = setInterval(() => {
                                const minutes = Math.floor(countdown / 60);
                                const seconds = countdown % 60;
                                interludeCountdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                                if (countdown == 3) {
                                    audio.play();
                                }

                                if (countdown <= 0) {
                                    clearInterval(countdownInterval);
                                    prayerInterludeModal.classList.remove('show');

                                    // Wait for fade-out animation to complete before showing next modal
                                    setTimeout(() => {
                                        // Show prayer modal
                                        if (prayerModal) {
                                            prayerModal.classList.add('show');
                                            // Schedule modal to hide after prayerEndIn minutes
                                            endTimeout = setTimeout(() => {
                                                // Add fade-out effect
                                                prayerModal.classList.remove('show');
                                            }, window.prayerEndIn * 60 * 1000);
                                        }
                                    }, 500);
                                }
                                countdown--;
                            }, 1000);
                            // console.log('Timer reached zero, scheduling modal');
                        }
                    }
                }
            }

            // Check every second
            setInterval(checkCountdown, 1000);
        });
    </script>
    <script src="{{ asset('js/public_display/time-calculator.js') }}"></script>
    <script src="{{ asset('js/public_display/kiosk-mode.js') }}"></script>
</body>
</html>
