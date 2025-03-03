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
                    <a href="{{ route('public_reports.index', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce]) }}" id="show-book-{{ $bookItem->id }}" class="btn {{ $bookItem->id == $selectedBook->id ? 'bm-bg-primary text-light' : 'bm-txt-primary' }}" style="border-top-left-radius: 9px;border-bottom-left-radius: 9px">
                        <i class="ti ti-book-2 fs-2"></i>&nbsp;{{ $bookItem->name }}
                        @if ($bookItem->id == $selectedBook->id)
                            &nbsp;<span class="ti float-end">&#xea5e;</span>
                        @endif
                    </a>
                    <button type="button" class="btn {{ $bookItem->id == $selectedBook->id ? 'bm-bg-primary text-light' : 'bm-txt-primary' }} dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" style="max-width: 2.5em;border-top-right-radius: 9px;border-bottom-right-radius: 9px">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('public_reports.finance.summary', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce] + Request::all()) }}"><i class="ti ti-home"></i>&nbsp;{{ __('report.'.$bookItem->report_periode_code) }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('public_reports.finance.categorized', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce] + Request::all()) }}"><i class="ti ti-package"></i>&nbsp;{{ __('report.finance_categorized') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('public_reports.finance.detailed', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce] + Request::all()) }}"> <i class="ti ti-alert-circle"></i>&nbsp;{{ __('report.finance_detailed') }}</a></li>
                    </ul>
                </div>
            @empty
                {{ __('book.not_found') }}
            @endforelse
        </div>
    </div>
</div>
