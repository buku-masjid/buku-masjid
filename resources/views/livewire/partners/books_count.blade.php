<div class="card shadow-lg" wire:init="getBooksCount" style="border-radius:1em; height: 10em">
    <div class="card-body p-3 row align-items-center" >
        <div class="col row align-items-center">
            <div class="col-9">
                <div class="h4 mb-1">Jenis Donasi</div>
                <div class="text-muted">Berdasarkan jumlah {{ __('book.book') }}</div>
            </div>
            <div class="col-3">
                @if ($isLoading)
                    <div class="loading-state text-center">
                        <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
                    </div>
                @else
                    <div class="display-4 font-weight-bold text-orange">{{ $booksCount }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
