@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')

@php
    $y1 = '0'; 
    $y2 = '5'; 
    $y3 = '10'; 
    $y4 = '15'; 
    $y5 = '20'; 
    $y6 = '25'; 
    $y7 = '30';
    $width = '4';
    $height = '4';
@endphp
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script> 
<script src="{{ asset('js/plugins/matrix-display.js') }}"></script>
<section class="bg-white">       
    <div class="container-md">
        <div id="matrix-display2" style="width: 100%; text-align: center" class="pt-2 pt-md-5">
            <div class="shadow-sm" style="width: auto; display: inline; border-radius: 10px; padding: 16px 10px; background-color: white; border: 1px solid #eee">  
                <svg class="matrix-svg" style="width: 299px; height: 38px">
                    <g transform="translate(-5,0)">
                        @for ($i = 1; $i <= 60; $i++)
                            <g transform="translate({{ $i * 5 }}, 0)">
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y1 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y2 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y3 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y4 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y5 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y6 }}" fill="#eeeeee"></rect>
                                <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y7 }}" fill="#eeeeee"></rect>
                            </g>
                        @endfor
                    </g>
                </svg>
            </div>
            <div class="text-black-50 fs-6 pt-2">Waktu bagian {{ Setting::get('masjid_city_name') }} dan sekitarnya</div>
        </div>
        <div class="section-hero row" style="padding-top:30px">
            <div class="col">
                @include('layouts._public_masjid_info')
            </div>
            <div class="d-none d-lg-block col-6 position-relative">
                @if (Setting::get('masjid_photo_path'))
                    <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
                @else
                    <div style="background-color: #f8f8f8; height: 360px"></div>
                @endif
                <img src="images/image_cover.svg" class="position-absolute top-0 start-0">
            </div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <div class="container-md home-bottom">
        <div>
            <div class="row align-items-end">
                @livewire('public-home.weekly-financial-summary')
            </div>
            </div>
            <div class="col-lg-12">
                <div class="row align-items-start">
                    <div class="col-lg-6 mt-3">
                        <div class="fs-4 pt-3 pb-3 d-flex align-items-center row">
                            <div class="col"><span class="fs-2 fw-bold pe-2">Program</span></div>
                            <div class="col"><a href="/infaq"><span class="pe-2 float-end">Detil Program <i class="ti">&#xea61;</i></span></a></div>
                        </div>
                        <div class="card">
                            <div>
                                <img src="storage/67b72e40dbd5a.webp" class="w-100 h-100 object-cover" alt="Tabungan Quban 1446 H" style="border-radius: 15px 15px 0px 0px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-3 mt-3">
                        @if (Route::has('lecturings.index'))
                            @livewire('public-home.daily-lecturings', ['date' => today(), 'dayTitle' => 'today'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    const cityName = "{{ Setting::get('masjid_city_name') }}";
    const cacheKey = `prayer_times_${cityName}`; // Unique key
    labelSholat = ['Imsak', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    jadwalSholat = [];

    // Check if data is in localStorage
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
        const data = JSON.parse(cachedData).data.jadwal;
        jadwalSholat = [data.imsak, data.subuh, data.dzuhur, data.ashar, data.maghrib, data.isya];
    } else {
        fetch(`/prayer-times/${cityName}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                if (data.data) {
                    localStorage.setItem(cacheKey, JSON.stringify(data)); // Store in localStorage
                    jadwalSholat = [data.data.jadwal.imsak, data.data.jadwal.subuh, data.data.jadwal.dzuhur, data.data.jadwal.ashar, data.data.jadwal.maghrib, data.data.jadwal.isya];
                }
            }
        });
    }

    jQuery(document).ready(function() { 
        function jadwalRemaining(){
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes(); 

            // Jadwal sholat berikutnya
            let nextIndex = jadwalSholat.findIndex(time => {
                const [hour, minute] = time.split(":").map(Number);
                return hour * 60 + minute > currentMinutes;
            });

            if (nextIndex === -1) {
                nextIndex = 0;
            }

            const [nextHour, nextMinute] = jadwalSholat[nextIndex].split(":").map(Number);
            const nextMinutes = nextHour * 60 + nextMinute;

            // hitung sisa waktu
            let remainingMinutes = nextMinutes - currentMinutes;
            if (remainingMinutes < 0) {
                remainingMinutes += 24 * 60;
            }

            const hoursLeft = Math.floor(remainingMinutes / 60);
            const minutesLeft = remainingMinutes % 60;

            return  hoursLeft +" Jam - "+ minutesLeft +" Menit  menuju  waktu  " + labelSholat[nextIndex];
        }

        let dm2;

        function updateMatrixDisplay() {
            // Remove previous MatrixDisplay if exists
            if (dm2) dm2.stop();

            // Initialize new MatrixDisplay with updated text
            dm2 = new MatrixDisplay({
                repeat: true,
                containerEl: '#matrix-display2 .matrix-svg',
                compositions: [
                    {
                        text: jadwalRemaining(),
                        fx: 'left',
                        colors: ['#20716b', '#169a90'],
                        background: '#EEE',
                        invert: false,
                        speed: 60
                    }
                ]
            });

            dm2.run();
        }
        updateMatrixDisplay();

        // Auto-update the display text every minute
        setInterval(updateMatrixDisplay, 60000);
    });
</script>
        
@endsection
