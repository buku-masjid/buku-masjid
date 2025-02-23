@extends('layouts.guest')

@section('title', __('lecturing.list'))

@section('content')

<?php
    /*
@include('public_schedules._nav')

@foreach ($audienceCodes as $audienceCode => $audience)
    @if (isset($lecturings[$audienceCode]))
        <div class="page-header my-4">
            <h2 class="page-title">{{ __('lecturing.audience_'.$audienceCode) }}</h2>
        </div>
        @foreach($lecturings[$audienceCode] as $lecturing)
            @include('public_schedules._single_'.$audienceCode)
        @endforeach
    @endif

@endforeach
*/
?>
<style>
    .pattern { display: none !important;}
</style>
<section class="bg-white">
    <div class="container-md">
        <div class="row section-hero pb-0 d-lg-flex align-items-stretch">
            <div class="col">
                @include('layouts._public_masjid_info')
            </div>

            <div class="d-none d-lg-flex col-7 justify-content-end align-items-end">
                <div>
                    <div class="d-flex align-items-end gap-2 align-items-end">
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 240px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat; background-position: 0 -30px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Imsak</h4>
                                <h1 class="m-0 d-block" id="waktu-0">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -95px -70px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Subuh</h4>
                                <h1 class="m-0 d-block" id="waktu-1">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 210px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -190px -60px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Dzuhur</h4>
                                <h1 class="m-0 d-block" id="waktu-2">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 270px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -285px 0px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Ashar</h4>
                                <h1 class="m-0 d-block" id="waktu-3">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -380px -70px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Maghrib</h4>
                                <h1 class="m-0 d-block" id="waktu-4">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 230px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -475px -40px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Isya</h4>
                                <h1 class="m-0 d-block" id="waktu-5">--.--</h3>
                            </div>
                        </div>
                    </div>
                    <div class="text-end fs-6 text-secondary pt-3">
                        Sumber: myquran.com<br>
                        Kota : {{ Setting::get('masjid_city_name') }} (berdasarkan lokasi masjid)<br>
                        <span id="timeRemaining"></span> lagi menuju waktu <span id="timeID"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="container-md position-relative">
        <!-- FILTER -->
           <div class="btn-toolbar d-flex justify-content-center row pt-4" style="position: relative; top: 20px" role="toolbar">
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <a href="{{ route('public_schedules.this_week', Request::all()) }}">
                    <button type="button" class="btn btn-light border bm-btn py-2">{{ __('time.this_week') }}</button>
                </a>
            </div>
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <a href="{{ route('public_schedules.next_week', Request::all()) }}">
                    <button type="button" class="btn btn-light border bm-btn py-2">{{ __('time.next_week') }}</button>
                </a>
            </div>
            <!--
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <button type="button" class="btn btn-light border bm-btn py-2"><i class="ti py-1">&#xea60;</i></button>
            </div>
            <div class="btn-group col col-sm-auto px-0" role="group">
                <button id="month" type="button" class="btn btn-light border bm-btn dropdown-toggle py-2" data-bs-toggle="dropdown" aria-expanded="false" >
                November
                </button>
                <ul class="dropdown-menu" aria-labelledby="month" style="width: 100%">
                    <li class="d-grid"><a class="dropdown-item" href="#">Januari</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Februari</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Maret</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">April</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Mei</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Juni</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Juli</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Agustus</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">September</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Oktober</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">November</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">Desember</a></li>
                </ul>
            </div>
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <button type="button" class="btn btn-light border bm-btn"><i class="ti py-1">&#xea61;</i></button>
            </div>
            <div class="btn-group col col-sm-auto px-0 d-none d-sm-inline-flex" role="group">
                <button id="year" type="button" class="btn btn-light border bm-btn dropdown-toggle py-2" data-bs-toggle="dropdown" aria-expanded="false">
                2024
                </button>
                <ul class="dropdown-menu" aria-labelledby="year" style="width: 100%">
                    <li class="d-grid"><a class="dropdown-item" href="#">2024</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">2023</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">2022</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">2021</a></li>
                    <li class="d-grid"><a class="dropdown-item" href="#">2020</a></li>
                </ul>
            </div> -->
        </div>
    </div>
</section>
<div class="section-bottom pb-5">
    <div class="container-md p-3 py-lg-0">
        <div class="pt-4 pt-lg-5">
            <!-- JUMAT -->
            <div class="row ">
                <div class="col-lg ps-sm-0">
                @foreach ($audienceCodes as $audienceCode => $audience)
                    @if ($audience == 'Jumat')
                        @if (isset($lecturings[$audienceCode]))
                            @foreach($lecturings[$audienceCode] as $lecturing)
                                @if ($lecturing->audience_code == 'friday' )
                                    @include('public_schedules._single_'.$audienceCode)
                                @endif
                            @endforeach
                        @else
                            <div class="container-xl my-auto card bg-light">
                                <div class="empty">
                                    <p class="empty-title">Belum ada jadwal</p>
                                    <p class="empty-subtitle text-secondary">
                                        Khutbah Ju'mat pada masjid belum tersedia.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
                </div>
            </div>
        </div>

        <div class="timeline_area pt-4 pt-lg-5">
            <div class="d-lg-flex justify-content-between pb-3">
                <h2 class="fw-bolder mb-3 ">Jadwal Kajian</h2>
                <!-- <div class="btn-group col col-sm-auto px-0" role="group">
                    <button id="year" type="button" class="btn btn-light border bm-btn dropdown-toggle py-2" data-bs-toggle="dropdown" aria-expanded="false">
                    Kajian Umum (Muslim & Muslimah)
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="year" style="width: 100%">
                        <li class="d-grid"><a class="dropdown-item" href="#">Kajian Umum (Muslim & Muslimah)</a></li>
                        <li class="d-grid"><a class="dropdown-item" href="#">Kajian Muslimah</a></li>
                        <li class="d-grid"><a class="dropdown-item" href="#">Kajian Muslim</a></li>
                    </ul>
                </div> -->
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Timeline Area-->
                    <div class="apland-timeline-area">
                        <!-- Single Timeline Content-->
                        <!-- WEEK -->
                        @foreach ($audienceCodes as $audienceCode => $audience)
                            @if ($audience != 'Jumat')
                                @if (isset($lecturings[$audienceCode]))
                                    <div class="single-timeline-area border-bottom py-4">
                                        <div class="d-none d-lg-flex timeline-date wow fadeInLeft" data-wow-delay="0.1s" >
                                            <p>{{ __('lecturing.audience_'.$audienceCode) }}</p>
                                        </div>
                                        <div class="row">
                                            @foreach($lecturings[$audienceCode] as $lecturing)
                                                @if ($lecturing->audience_code != 'friday' )
                                                    @include('public_schedules._single_'.$audienceCode)
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="container-xl my-4 card bg-light">
                                        <div class="empty">
                                            <p class="empty-title">Kajian {{ __('lecturing.audience_'.$audienceCode) }}</p>
                                            <p class="empty-subtitle text-secondary">
                                                Jadwal Kajian untuk {{ __('lecturing.audience_'.$audienceCode) }} pada masjid ini belum tersedia.
                                            </p>
                                        </div>
                                    </div>                                 
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const cityName = "{{ Setting::get('masjid_city_name') }}";
    const cacheKey = `prayer_times_${cityName}`; // Unique key
    labelSholat = ['Imsak', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    jadwalSholat = [];

    // Check if data is in localStorage
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
        const data = JSON.parse(cachedData).data.jadwal;
        jadwalSholat = [data.imsak, data.subuh, data.dzuhur, data.ashar, data.maghrib, data.isya];
        jadwalSholat.forEach((waktu, index) => {
            const element = document.getElementById(`waktu-${index}`);
            if (element) {
                element.textContent = waktu;
            }
        });
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
                    jadwalSholat.forEach((waktu, index) => {
                        const element = document.getElementById(`waktu-${index}`);
                        if (element) {
                            element.textContent = waktu;
                        }
                    });
                }
            }
        });
    }

    function jadwalRemaining(timeid, labelid){
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

        document.getElementById(labelid).textContent = labelSholat[nextIndex];
        document.getElementById(timeid).textContent = hoursLeft +" Jam : "+ minutesLeft +" Menit";
    }

    jadwalRemaining('timeRemaining','timeID');
</script>
@endsection