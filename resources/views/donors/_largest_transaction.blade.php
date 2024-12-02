<div class="card shadow-lg" style="border-radius:1em;height: 10em">
    <div class="card-body p-3 pl-5">
        @if ($largestTransaction)
            <span class="badge p-2 bg-blue-lighter text-dark float-right">
                {{ __('book.book') }}: {{ $largestTransaction->book->name }}
            </span>
        @endif
        <div>
            <div class="strong pt-3">{{ __('partner.largest_transaction') }}</div>
            <div class="text-muted small">
                @if ($largestTransaction)
                    {{ $largestTransaction->day_name }},
                    {{ Carbon\Carbon::parse($largestTransaction->date)->isoFormat('DD MMM YYYY') }}
                @else
                    -
                @endif
            </div>
        </div>
        <div>
            @if ($largestTransaction)
                <div class="h1 mt-4" style="color: {{ $largestTransaction->in_out ? config('masjid.income_color') : config('masjid.spending_color') }}">
                    {{ config('money.currency_code') }}
                    {{ $largestTransaction->in_out ? '' : '-' }}
                    {{ format_number($largestTransaction->amount) }}
                </div>
            @else
                <div class="h1 mt-4" style="color: {{ config('masjid.income_color') }}">{{ config('money.currency_code') }} 0</div>
            @endif
        </div>
    </div>
</div>
