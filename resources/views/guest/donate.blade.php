@extends('layouts.guest')

@section('title', __('app.donate'))

@section('content')
<style>
    .pattern { display: none !important;}
</style>
<section class="bg-white">
    <div class="container-md">
        <div class="masjid-info-top row">
            @include('layouts._public_infomasjid')
            <div class="d-none d-lg-flex align-items-center col-7 position-relative">
                <img src="images/donate_infaq.png" style="width: 80%">
                <img src="images/donate_pattern.svg" class="position-absolute" style="z-index: 0">
            </div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <div class="container-md p-3 home-bottom">
        <h2 class="fw-bolder mb-3">Infaq</h2>
        <div class="row row-cols-lg-3">
            <div class="col-lg ps-sm-0">
                <div class="card fw-bold p-3 mb-2 shadow-lg">
                    <!-- QRIS -->
                    <div class="modal fade" id="qris1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">QRIS BSI</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="images/temp-qris.jpg">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 bottom-0 me-3 px-2 py-1 mb-3" data-bs-toggle="modal" data-bs-target="#qris1">
                        Lihat QRIS
                    </button>
                    <div class="mb-3 py-2 px-3 fs-3" style="border-radius: 10px; border: 1px solid #cfeeeb; background-color: #F4FFFF">
                        Rek. Sedekah Subuh
                    </div>
                    <div class="row">
                        <div class="col-auto lh-1">
                            <span class="date">Nama Bank</span><br>
                            <h3>Bank BSI</h3>
                        </div>
                        <div class="col-lg lh-1 pt-3 pt-lg-0">
                            <span class="date">Atas Nama</span><br>
                            <h3>Munawir Munawir</h3>
                        </div>
                    </div>

                    <div class="pt-3 pt-lg-4">
                        <span class="date">No Rek</span><br>
                        <h1 class=" bm-txt-primary fw-bolder">7199793588</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg ps-sm-0">
                <div class="card fw-bold p-3 mb-2 shadow-lg">
                    <button type="button" class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 bottom-0 me-3 px-2 py-1 mb-3" data-bs-toggle="modal" data-bs-target="#qris1">
                        Lihat QRIS
                    </button>
                    <div class="mb-3 py-2 px-3 fs-3" style="border-radius: 10px; border: 1px solid #cfeeeb; background-color: #F4FFFF">
                        Rek. Donasi Operasional Masjid
                    </div>
                    <div class="row">
                        <div class="col-auto lh-1">
                            <span class="date">Nama Bank</span><br>
                            <h2>Bank BSI</h3>
                        </div>
                        <div class="col-lg lh-1 pt-3 pt-lg-0">
                            <span class="date">Atas Nama</span><br>
                            <h3>Munawir Munawir</h3>
                        </div>
                    </div>
                    <div class="pt-3 pt-lg-4">
                        <span class="date">No Rek</span><br>
                        <h1 class=" bm-txt-primary fw-bolder">7199793588</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-3 mt-4 border-top">
        <h2 class="fw-bolder mb-3">DKM Masjid</h2>
        </div>
        <div class="card p-4 fs-3 shadow-sm">
            <div>
                Badan Penasihat
                <div class="row py-3 gap-3 d-flex justify-content-lg-start justify-content-center">
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-3 mt-3 border-top">
                Operasional
                <div class="row py-3 gap-3 d-flex justify-content-lg-start justify-content-center">
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                    <div class="col-auto photo-profile lh-1 text-center">
                        <div class="photo bg-secondary bm-btn mb-3 d-flex align-items-center justify-content-center">
                            <i class="ti text-light">&#xfd19;</i>
                        </div>
                        <div class="profile">
                            <span class="fs-3">Kuncoro Adi</span><br>
                            <span class="fs-5 text-secondary">Penasihat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts._public_footer')
@endsection
