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
                {{--
                <div class="col-lg-3 ps-sm-0">
                    <div class="fs-2 fw-bold pb-3 pt-sm-3">
                        <br class="d-sm-none">
                        Infaq
                    </div>
                    <div class="card fw-bold p-3 mb-2 bm-section-selected shadow-lg">
                        <div class="d-flex flex-row">
                            <div class="lh-1">
                                <span class="date">Bank</span><br>
                                Bank BSI
                            </div>
                            <div class="lh-1 ms-4">
                                <span class="date">Atas Nama</span><br>
                                Munawir
                            </div>
                        </div>

                        <h1 class="pt-4 fw-bolder">7199793588</h1>
                    </div>
                </div>
                --}}
            </div>
        </div>
    </div>
</div>

@endsection
