@extends('layouts.guest')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')
<section class="bg-white">
    <div class="container-md px-0">
        <div class="row p-3 p-sm-0 py-sm-3 align-items-center">
            <div class="col-auto">
                @if (Setting::get('masjid_logo_path'))
                    <div class="mb-3"><img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 80px"></div>
                @endif

            </div>
            <div class="col fs-2 fw-bold lh-sm text-dark">
                Masjid<br>
                {{ Setting::get('masjid_name', config('masjid.name')) }}
            </div>
        </div>
    </div>
</section>
<div class="section-bottom pb-5">
    <div class="container-md">
        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="books">
            <div class="offcanvas-header pt-3 pb-1 border-0 justify-content-center">
                <h2 class="offcanvas-title" id="offcanvasBottomLabel">Pilih Laporan</h2>
            </div>
            <div class="offcanvas-body">
                <div class="d-sm-flex justify-content-center gap-2 book-list">
                    <a href="">
                        <div class="bm-btn book book-selected bm-txt-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                            <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                            <path d="M9 8h6" />
                            </svg>&nbsp;Kegiatan Rutin
                            <span class="ti float-end">&#xea5e;</span>
                        </div>
                    </a>
                    <a href="">
                        <div class="bm-btn book bm-txt-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                            <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                            <path d="M9 8h6" />
                            </svg>&nbsp;Pembangunan Masjid
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row px-3 pt-3 p-lg-0 pt-lg-3">
                <div class="col-sm-auto fs-2 fw-bold pb-3 pb-sm-0 d-sm-flex align-items-center">Laporan</div>
                <div class="col-sm d-grid d-sm-flex align-items-center pb-2 pb-sm-0">
                    <button type="button" class="btn btn-teal bm-btn justify-content-between" data-bs-toggle="offcanvas" data-bs-target="#books" aria-controls="books">
                        <div>Kegiatan Rutin</div>
                        <div class="ms-2"><i class="ti">&#xea61;</i></div>
                    </button>
                </div>
                <div class="col px-3 text-sm-end">
                    <div class="btn-toolbar d-flex d-sm-block justify-content-between row" role="toolbar">
                        <div class="btn-group col-auto" role="group" aria-label="Third group">
                            <button type="button" class="btn btn-light border bm-btn py-2"><i class="ti py-1">&#xea60;</i></button>
                        </div>
                        <div class="btn-group col col-sm-auto px-0" role="group">
                            <button id="month" type="button" class="btn btn-light border bm-btn dropdown-toggle py-2" data-bs-toggle="dropdown" aria-expanded="false">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="summary px-3 px-lg-0 py-2 pt-lg-0">
                <div class="row pt-2 pt-sm-3 gap-2">
                    <div class="col pe-0">
                        <div class="card fw-bold p-3 mb-2 shadow">
                            Pemasukan<br>
                            <span class="date">7 Oktober 2024</span>
                            <h1 class="pt-4 bm-txt-primary fw-bolder">Rp 570.000</h1>
                            <i class="ti fe-bold bm-txt-primary bm-bg-primary-soft position-absolute top-50 end-0 translate-middle p-2">&#xea13;</i>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="card fw-bold p-3 mb-2 shadow">
                            Pengeluaran<br>
                            <span class="date">7 Oktober 2024</span>
                            <h1 class="pt-4 bm-txt-out fw-bolder">Rp 570.000</h1>
                            <i class="ti fe-bold bm-txt-out bm-bg-out-soft position-absolute top-50 end-0 translate-middle p-2">&#xea24;</i>
                        </div>
                    </div>
                    <div class="col ps-0">
                        <div class="card fw-bold p-3 mb-2 shadow">
                            Saldo Terakhir<br>
                            <span class="date">7 Oktober 2024</span>
                            <h1 class="pt-4 bm-txt-netral fw-bolder">Rp 570.000</h1>
                            <i class="ti fe-normal bm-txt-netral bm-bg-netral-soft position-absolute top-50 end-0 translate-middle p-2">&#xeb75;</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-3 p-lg-0">
                <div class="accordion accordion-flush">
                    <div class="accordion-item card mb-2">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pekan2" aria-expanded="false">
                                Pekan 2
                            </button>
                        </div>
                        <div id="pekan2" class="accordion-collapse collapse px-lg-4 py-lg-3 show">
                            <div class="accordion-body pt-0">
                                <div class="row">
                                    <div class="col-auto d-none"></div>
                                    <div class="col me-1 bm-fade p-2 fs-3 fw-bold rounded">Transaksi</div>
                                    <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">Pemasukan</div>
                                    <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">Pengeluaran</div>
                                </div>
                                <div class="accordion accordion-flush transaction">
                                    <div class="accordion-item">
                                        <div class="p-0 collapsed" data-bs-toggle="collapse" data-bs-target="#tw2-1" aria-expanded="false">
                                            <div class="row">
                                                <div class="col-auto d-sm-none d-flex align-items-center"><i class="ti icon fe-bold bm-txt-primary">&#xea13;</i></div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col col-cat">Kotak Infaq Kajian</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold">Rp 325.000</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tw2-1" class="accordion-collapse collapse">
                                            <div class="accordion-body transaction-list">
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Edaran</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Diluar</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0 ">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Edaran</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Diluar</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="p-0 collapsed" data-bs-toggle="collapse" data-bs-target="#tw2-2" aria-expanded="false">
                                            <div class="row">
                                                <div class="col-auto d-sm-none d-flex align-items-center"><i class="ti icon fw-bold bm-txt-out">&#xea24;</i></div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col col-cat">Pengeluaran Bulanan</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold"></div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold">Rp 1.500.000</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tw2-2" class="accordion-collapse collapse">
                                            <div class="accordion-body transaction-list tl-out">
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Pembayaran Listrik</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Air PDAM</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- DEMO LIST -->
                    <div class="accordion-item card mb-2">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pekan1" aria-expanded="false">
                                Pekan 1
                            </button>
                        </div>
                        <div id="pekan1" class="accordion-collapse collapse px-lg-4 py-lg-3">
                            <div class="accordion-body pt-0">
                                <div class="row">
                                    <div class="col-auto d-none"></div>
                                    <div class="col me-1 bm-fade p-2 fs-3 fw-bold rounded">Transaksi</div>
                                    <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">Pemasukan</div>
                                    <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">Pengeluaran</div>
                                </div>
                                <div class="accordion accordion-flush transaction">
                                    <div class="accordion-item">
                                        <div class="p-0 collapsed" data-bs-toggle="collapse" data-bs-target="#tw1-1" aria-expanded="false">
                                            <div class="row">
                                                <div class="col-auto d-sm-none d-flex align-items-center"><i class="ti icon fe-bold bm-txt-primary">&#xea13;</i></div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col col-cat">Kotak Infaq Kajian</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold">Rp 325.000</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div id="tw1-1" class="accordion-collapse collapse">
                                            <div class="accordion-body transaction-list">
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Edaran</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Diluar</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0 ">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Edaran</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Kotak Infaq Diluar</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div class="p-0 collapsed" data-bs-toggle="collapse" data-bs-target="#tw1-2" aria-expanded="false">
                                            <div class="row">
                                                <div class="col-auto d-sm-none d-flex align-items-center"><i class="ti icon fw-bold bm-txt-out">&#xea24;</i></div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col col-cat">Pengeluaran Bulanan</div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold"></div>
                                                        <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold">Rp 1.500.000</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tw1-2" class="accordion-collapse collapse">
                                        <div class="accordion-body transaction-list tl-out">
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Pembayaran Listrik</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 100.000</div>
                                                </div>
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">12/11/24</div>
                                                    <div class="col-lg me-1 py-lg-2">Air PDAM</div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold"></div>
                                                    <div class="col-lg-2 py-lg-2 col-num fw-bold">Rp 150.000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 px-sm-0">
                <div class="row fs-3 py-3 px-lg-4 py-lg-3">
                    <div class="col text-center text-sm-start fs-2 fw-bold py-3">Kas Oktober 2024</div>
                    <div class="col-sm-auto text-center pb-2 pb-sm-0 text-sm-end">
                        Pemasukan
                        <h2 class="bm-txt-primary ">Rp 570.000</h2>
                    </div>
                    <div class="col-sm-auto text-center pt-3 pt-sm-0  text-sm-end">
                        Pengeluaran
                        <h2 class="bm-txt-out ">Rp 570.000</h2>
                    </div>
                </div>
                <div class="fs-3 fw-bold text-center text-sm-end border-top pt-3 px-lg-4">
                    Saldo
                    <h1 class="bm-txt-netral fw-bolder">Rp 570.000</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts._public_footer')
@endsection

@section('styles')
<style>
.list-group-transparent .list-group-item {
    padding: 0.5rem 0.5rem;
}
</style>
@endsection
