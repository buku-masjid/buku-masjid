@if (Setting::for($book)->get('landing_page_content'))
    {!! Purify::clean(Setting::for($book)->get('landing_page_content')) !!}
@else
    {{ $book->description }}
@endif

@if (isset($book->bank_account_id))
<div class="pt-3">
    <h3 class="pb-2">Rekening Donasi</h3>
    <div class="col-lg ps-sm-0">
        <div class="card fw-bold p-4 shadow-lg">
            @if (Setting::for($book->bankAccount)->get('qris_image_path'))
                <div class="modal fade" id="qris-{{ $book->bankAccount->number }}" tabindex="-1" aria-labelledby="modalLabel{{ $book->bankAccount->number }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel{{ $book->bankAccount->number }}">{{ __('bank_account.qris') }} {{ $book->bankAccount->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ Storage::url(Setting::for($book->bankAccount)->get('qris_image_path'))}}" alt="{{ __('bank_account.qris') }}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 top-0 me-3 px-2 py-1 mt-3" data-bs-toggle="modal" data-bs-target="#qris-{{ $book->bankAccount->number }}">
                    {{ __('bank_account.show_qris') }}
                </button>
            @endif
            <div class="row">
                <div class="col-auto lh-1">
                    <span class="fs-5 fw-light secondary-text">{{ __('bank_account.name') }}</span><br>
                    <h3>{{ $book->bankAccount->name }}</h3>
                </div>
                <div class="col-lg lh-1 pt-3 pt-lg-0">
                    <span class="fs-5 fw-light secondary-text">{{ __('bank_account.account_name') }}</span><br>
                    <h3>{{ $book->bankAccount->account_name }}</h3>
                </div>
            </div>

            <div class="pt-3 pt-lg-2">
                <span class="fs-5 fw-light secondary-text">{{ __('bank_account.number') }}</span><br>
                <h1 class=" bm-txt-primary fw-bolder">{{ $book->bankAccount->number }}</h1>
            </div>
        </div>
    </div>
</div>
@endif
