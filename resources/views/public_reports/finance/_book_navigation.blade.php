<button type="button" class="btn btn-teal bm-btn justify-content-between" data-bs-toggle="offcanvas" data-bs-target="#books" aria-controls="books">
    <div>{{ $selectedBook->name }}</div>
    <div class="ms-2 d-flex align-items-center">
        <span class="badge bg-light text-dark" style="letter-spacing: normal; font-size: 10px" title="{{ __('report.periode') }}: {{ __('report.'.$selectedBook->report_periode_code) }}">{{ __('report.'.$selectedBook->report_periode_code) }}
        </span><i class="ti">&#xea61;</i>
    </div>
</button>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="books">
    <div class="offcanvas-header pt-2 pb-0 border-0 justify-content-center">
        <h2 class="offcanvas-title" id="offcanvasBottomLabel">{{ __('report.select_report') }}</h2>
    </div>
    <div class="offcanvas-body">
        <div class="row justify-content-center gap-2">
            @forelse ($books as $bookItem)
                <div class="col-md-3 btn-group">
                    <a href="{{ route('public_reports.index', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce]) }}" id="show-book-{{ $bookItem->id }}" class="btn {{ $bookItem->id == $selectedBook->id ? 'bm-bg-primary text-light' : 'bm-txt-primary' }}" style="border-radius: 9px">
                        <i class="ti ti-book-2 fs-2"></i>&nbsp;{{ $bookItem->name }}
                        @if ($bookItem->id == $selectedBook->id)
                            &nbsp;<span class="ti float-end">&#xea5e;</span>
                        @endif
                    </a>
                </div>
            @empty
                {{ __('book.not_found') }}
            @endforelse
        </div>
    </div>
</div>
