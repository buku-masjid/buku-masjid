<div wire:init="getTotalIncomeFromPartner">
    @if ($isLoading)
        <div class="loading-state text-center">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="lead">
            {{ __('transaction.income') }}
            {{ get_months()[$month] ?? '' }}
            {{ $year }}
        </div>
        <div class="text-muted my-1">{{ __('app.update') }}: {{ $endDate }}</div>
        <div class="h1" style="color: {{ config('masjid.income_color') }}">
            {{ config('money.currency_code') }} {{ format_number($totalIncomeFromPartner) }}
        </div>
    @endif
</div>
