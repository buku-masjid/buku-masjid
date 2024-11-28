<div class="card shadow-lg" wire:init="getDonorsCount" style="border-radius:1em; height: 10em">
    <div class="card-body p-3 row align-items-center" >
        <div class="col row align-items-center">
            <div class="col-8">
                <div class="h4 mb-1">{{ __('donor.donors_count') }}</div>
                <div class="text-muted">
                    @if ($book)
                        {{ __('donor.donors_count_of_book', ['book_name' => $book->name]) }}
                    @else
                        {{ __('donor.total_donating') }}
                    @endif
                    {{ get_months()[$month] ?? '' }}
                    {{ $year != '0000' ? $year : '' }}
                </div>
            </div>
            <div class="col-4 text-right">
                @if ($isLoading)
                    <div class="loading-state">
                        <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
                    </div>
                @else
                    <div class="display-4 font-weight-bold text-orange">{{ $donorsCount }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
