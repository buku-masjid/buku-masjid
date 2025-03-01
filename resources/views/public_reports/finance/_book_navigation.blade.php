<button type="button" class="btn btn-teal bm-btn justify-content-between" data-bs-toggle="offcanvas" data-bs-target="#books" aria-controls="books">
    <div>{{ $selectedBook->name }}</div>
    <div class="ms-2 d-flex align-items-center">
        <span class="badge bg-light text-dark" style="letter-spacing: normal; font-size: 10px" title="{{ __('report.periode') }}: {{ __('report.'.$selectedBook->report_periode_code) }}">{{ __('report.'.$selectedBook->report_periode_code) }}
        </span><i class="ti">&#xea61;</i>
    </div>
</button>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="books">
    <div class="offcanvas-header pt-3 pb-1 border-0 justify-content-center">
        <h2 class="offcanvas-title" id="offcanvasBottomLabel">Pilih Laporan</h2>
    </div>
    <div class="offcanvas-body">
        <div class="d-sm-flex justify-content-center gap-2 book-list">
            @forelse ($books as $bookItem)
                <a href="{{ route('public_reports.index', ['active_book_id' => $bookItem->id, 'nonce' => $bookItem->nonce]) }}" id="show-book-{{ $bookItem->id }}">
                    <div class="bm-btn book {{ $bookItem->id == $selectedBook->id ? 'book-selected' : '' }} bm-txt-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                        <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                        <path d="M9 8h6" />
                        </svg>&nbsp;{{ $bookItem->name }}
                        @if ($bookItem->id == $selectedBook->id)
                            <span class="ti float-end">&#xea5e;</span>
                        @endif
                    </div>
                </a>
            @empty
                {{ __('book.not_found') }}
            @endforelse
        </div>
    </div>
</div>
