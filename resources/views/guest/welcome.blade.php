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
                <!--<div class="col-lg-9">
                    <div class="fs-4 pt-3 pb-3 d-flex align-items-center">
                        <span class="fs-2 fw-bold pe-2">Laporan Pekan Ini</span>
                        <span class="badge bg-cyan-lt">Kegiatan Rutin</span>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-lg ps-sm-0">
                            <div class="card fw-bold p-3 mb-2 shadow-lg">
                                Pemasukan<br>
                                <span class="date">7 Oktober 2024</span>
                                <h1 class="pt-4 bm-txt-primary fw-bolder">Rp 570.000</h1>
                            </div>
                        </div>
                        <div class="col-lg ps-sm-0">
                            <div class="card fw-bold p-3 mb-2 shadow-lg">
                                Pengeluaran<br>
                                <span class="date">7 Oktober 2024</span>
                                <h1 class="pt-4 bm-txt-out fw-bolder">Rp 570.000</h1>
                            </div>
                        </div>
                        <div class="col-lg ps-sm-0">
                            <div class="card fs-3 fw-bold p-3 mb-2 position-relative shadow-lg">
                                <button type="button" class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 me-3 px-2 py-1">
                                Lebih Detil
                                </button>
                                Saldo Terakhir<br>
                                <span class="date">7 Oktober 2024</span>
                                <h1 class="pt-4 bm-txt-netral fw-bolder">Rp 570.000</h1>
                            </div>
                        </div>
                    </div>
                    <a class="d-sm-none">
                        <button type="button" class="btn bm-btn btn-sm btn-ghost-cyan mt-2">
                            &nbsp;Lihat Semua Laporan <i class="ti">&#xea1c;</i>&nbsp;
                        </button>
                    </a>
                </div>-->
                @livewire('public-home.weekly-financial-summary')
                <div class="col ps-sm-0">
                    <div class="fs-2 fw-bold pb-3">
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
            </div>

        </div>
        <div class="text-center py-4 d-sm-none">
            (c) 2024 Bukumasjid
        </div>
        <div class=" py-4 d-none d-sm-block position-relative">
            (c) 2024 Bukumasjid. <i class="ti ps-4">&#xf7e6;</i> bukumasjid <i class="ti ps-2">&#xf7eb;</i> bukumasjid <i class="ti ps-2">&#xec26;</i> bukumasjid <i class="ti ps-2">&#xec20;</i> bukumasjid
            <div class="p-10 cta-join position-absolute top-0 end-0 me-3">
                Ingin kelola finansial masjid Anda ? <br>
                <span>Gabung ke BukuMasjid</span>
            </div>
        </div>
    </div>
</div>
@endsection
