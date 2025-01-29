@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')

<section class="bg-white">
    <div class="container-md">
        <div class="masjid-info-top row">
            @include('layouts._public_infomasjid')
            <div class="d-none d-lg-block col-7 position-relative">
                <img src="images/photo_masjid.png">
                <img src="images/image_cover.svg" class="position-absolute top-0 start-0">
            </div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <div class="container-md home-bottom">
        <div>
            <div class="row p-3 align-items-end">
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
