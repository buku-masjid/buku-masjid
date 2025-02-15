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
                                <h1 class="m-0 d-block" id="imsak">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -95px -70px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Subuh</h4>
                                <h1 class="m-0 d-block" id="subuh">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 210px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -190px -60px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Dzuhur</h4>
                                <h1 class="m-0 d-block" id="dzuhur">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 270px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -285px 0px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Ashar</h4>
                                <h1 class="m-0 d-block" id="ashar">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -380px -70px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Maghrib</h4>
                                <h1 class="m-0 d-block" id="maghrib">--.--</h3>
                            </div>
                        </div>
                        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 230px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -475px -40px">
                            <div class="prayinfo">
                                <h4 class="m-0 d-flex">Isya</h4>
                                <h1 class="m-0 d-block" id="isya">--.--</h3>
                            </div>
                        </div>
                    </div>
                    <div class="text-end fs-6 text-secondary pt-3">
                        Sumber: myquran.com<br>
                        Kota : {{ Setting::get('masjid_city_name') }} (berdasarkan lokasi masjid)
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
                        <?php
                            /*
                        <!-- WEEK -->
                        <div class="single-timeline-area border-bottom py-4">
                            <div class="d-none d-lg-flex timeline-date wow fadeInLeft" data-wow-delay="0.1s">
                                <p>Pekan 2</p>
                            </div>
                            <div class="row">
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- WEEK -->
                        <div class="single-timeline-area border-bottom py-4">
                            <div class="d-none d-lg-flex timeline-date wow fadeInLeft" data-wow-delay="0.1s">
                                <p>Pekan 3</p>
                            </div>
                            <div class="row">
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>
                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- WEEK -->
                        <div class="single-timeline-area border-bottom py-4">
                            <div class="d-none d-lg-flex timeline-date wow fadeInLeft" data-wow-delay="0.1s">
                                <p>Pekan 4</p>
                            </div>
                            <div class="row">
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SCHEDULE ITEM -->
                                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                    <div class="text-secondary fs-5 row">
                                        <div class="col-auto"><i class="ti">&#xea52;</i> 11 Nov 2024 </div>
                                        <div class="col-auto"><i class="ti">&#xf319;</i> 10:00 - Selesai</div>
                                    </div>
                                    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div>
                                            <div class="timeline-icon"></div>

                                        </div>
                                        <div class="timeline-text">
                                            <h5 class="text-secondary">Kajian Bada Subuh</h5>
                                            <p>Tradisi Keagamaan Masyarakat (adat istiadat) dalam tinjauan Islam.</p>
                                            <div class="lh-1 pt-3">
                                                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                                                <p class="bm-txt-primary fw-bold">Ust Adi Hidayat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        */
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const cityName = "{{ Setting::get('masjid_city_name') }}";
    const cacheKey = `prayer_times_${cityName}`; // Unique key

    // Check if data is in localStorage
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
            const data = JSON.parse(cachedData);
        // ... use the cached data ...
        if (data.data) {
            if (Array.isArray(data.data)){
                data.data.forEach(item => {
                    document.getElementById('imsak').textContent = item.imsak;
                    document.getElementById('subuh').textContent = item.subuh;
                    document.getElementById('dzuhur').textContent = item.dzuhur;
                    document.getElementById('ashar').textContent = item.ashar;
                    document.getElementById('maghrib').textContent = item.maghrib;
                    document.getElementById('isya').textContent = item.isya;
                });
            } else {
                document.getElementById('imsak').textContent = data.data.jadwal.imsak;
                document.getElementById('subuh').textContent = data.data.jadwal.subuh;
                document.getElementById('dzuhur').textContent = data.data.jadwal.dzuhur;
                document.getElementById('ashar').textContent = data.data.jadwal.ashar;
                document.getElementById('maghrib').textContent = data.data.jadwal.maghrib;
                document.getElementById('isya').textContent = data.data.jadwal.isya;
            }
        }
    } else {
        fetch(`/prayer-times/${cityName}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                if (data.data) {
                    // ... use the fetched data ...
                    localStorage.setItem(cacheKey, JSON.stringify(data)); // Store in localStorage

                    if (Array.isArray(data.data)){
                        data.data.forEach(item => {
                            document.getElementById('imsak').textContent = item.imsak;
                            document.getElementById('subuh').textContent = item.subuh;
                            document.getElementById('dzuhur').textContent = item.dzuhur;
                            document.getElementById('ashar').textContent = item.ashar;
                            document.getElementById('maghrib').textContent = item.maghrib;
                            document.getElementById('isya').textContent = item.isya;
                        });
                    } else {
                        document.getElementById('imsak').textContent = data.data.jadwal.imsak;
                        document.getElementById('subuh').textContent = data.data.jadwal.subuh;
                        document.getElementById('dzuhur').textContent = data.data.jadwal.dzuhur;
                        document.getElementById('ashar').textContent = data.data.jadwal.ashar;
                        document.getElementById('maghrib').textContent = data.data.jadwal.maghrib;
                        document.getElementById('isya').textContent = data.data.jadwal.isya;
                    }
                }
            }
        });
    }
    /*fetch('/prayer-times/{{ Setting::get('masjid_city_name') }}')
    .then(response => response.json())
    .then(data => {
    if (data.error) {
      console.error("Error:", data.error); // Handle errors
      // Display the error message to the user or take other actions
    } else {
      if (data.data) {
        if (Array.isArray(data.data)){
          data.data.forEach(item => {
            document.getElementById('imsak').textContent = item.imsak;
            document.getElementById('subuh').textContent = item.subuh;
            document.getElementById('dzuhur').textContent = item.dzuhur;
            document.getElementById('ashar').textContent = item.ashar;
            document.getElementById('maghrib').textContent = item.maghrib;
            document.getElementById('isya').textContent = item.isya;
            // ... update other HTML elements
          });
        } else {
          document.getElementById('imsak').textContent = data.data.jadwal.imsak;
          document.getElementById('subuh').textContent = data.data.jadwal.subuh;
          document.getElementById('dzuhur').textContent = data.data.jadwal.dzuhur;
          document.getElementById('ashar').textContent = data.data.jadwal.ashar;
          document.getElementById('maghrib').textContent = data.data.jadwal.maghrib;
          document.getElementById('isya').textContent = data.data.jadwal.isya;
          // ... update other HTML elements
        }
      }

    }
  })
  .catch(error => {
    console.error("Fetch Error:", error); // Handle fetch errors
  }); */
</script>
@endsection