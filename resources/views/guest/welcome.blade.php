@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')

<section class="bg-white">
    <div class="container-md">
        <div class="section-hero row">
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
                                <img src="storage/67b7372e49cb1.webp" class="w-100 h-100 object-cover" alt="Tabungan Quban 1446 H" style="border-radius: 15px 15px 0px 0px;">
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

@endsection
