<div class="card shadow-lg" wire:init="getDonorsCount" style="border-radius:1em; height: 10em">
    <div class="card-body p-3 row align-items-center" >
        <div class="col row align-items-center">
            <div class="col-8">
                <div class="h4 mb-1">{{ __('donor.donors') }}</div>
                <div class="text-muted">{{ __('donor.donors_count') }}</div>
            </div>
            <div class="col-4">
                @if ($isLoading)
                    <div class="loading-state text-center">
                        <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
                    </div>
                @else
                    <div class="display-4 font-weight-bold text-orange">{{ $donorsCount }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
