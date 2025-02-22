<?php /* @if (Setting::for($book)->get('poster_image_path'))
    <img class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}">
    <hr>
@endif */
?>
@if (Setting::for($book)->get('landing_page_content'))
    {!! Purify::clean(Setting::for($book)->get('landing_page_content')) !!}
@else
    {{ $book->description }}
@endif

<div class="pt-3">
    <h3 class="pb-2">Rekening Donasi</h3>
    @if (isset($book->bank_account_id))
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-4 shadow-lg">
                <!-- QRIS -->
                @if (Setting::for($book->bankAccount)->get('qris_image_path'))
                    <div class="modal fade" id="qris-{{ $book->bankAccount->number }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">QRIS BSI</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ Storage::url(Setting::for($book->bankAccount)->get('qris_image_path'))}}" alt="QRIS">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 top-0 me-3 px-2 py-1 mt-3" data-bs-toggle="modal" data-bs-target="#qris-{{ $book->bankAccount->number }}">
                        Lihat QRIS
                    </button>
                @endif
                <div class="row">
                    <div class="col-auto lh-1">
                        <span class="fs-5 fw-light secondary-text">Nama Bank</span><br>
                        <h3>{{ $book->bankAccount->name }}</h3>
                    </div>
                    <div class="col-lg lh-1 pt-3 pt-lg-0">
                        <span class="fs-5 fw-light secondary-text">Atas Nama</span><br>
                        <h3>{{ $book->bankAccount->account_name }}</h3>
                    </div>
                </div>

                <div class="pt-3 pt-lg-2">
                    <span class="fs-5 fw-light secondary-text">No Rek</span><br>
                    <h1 class=" bm-txt-primary fw-bolder">{{ $book->bankAccount->number }}</h1>
                </div>
            </div>
        </div>
    @else
        <div class="row ">
            <div class="col-lg ps-sm-0">
                <div class="container-xl my-auto card bg-light">
                    <div class="empty">
                        <p class="empty-title">Rekening belum terdaftar</p>
                        <p class="empty-subtitle text-secondary">
                            Untuk memaksimalkan program masjid, tambahkan rekening donasi ini untuk jamaah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


