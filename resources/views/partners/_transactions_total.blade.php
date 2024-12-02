<div class="card shadow-lg" style="border-radius:1em;height: 10em">
    <div class="card-body p-3 pl-5">
        <div>
            <div class="strong pt-3">{{ __('transaction.total') }}</div>
            <div class="text-muted small">
                {{ __('app.update') }}:
                @if (!$partner->transactions->isEmpty())
                    @php
                        $lastTransaction = $partner->transactions->sortBy('date')->last();
                    @endphp
                    {{ $lastTransaction->day_name }},
                    {{ Carbon\Carbon::parse($lastTransaction->date)->isoFormat('DD MMM YYYY') }}
                @else
                    -
                @endif
            </div>
        </div>
        <div>
            @if (!$partner->transactions->isEmpty())
                @php
                    $transactionTotal = $partner->transactions->sum(function ($transaction) {
                        return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                    });
                @endphp
                <div class="h1 mt-4" style="color: {{ $transactionTotal >= 0 ? config('masjid.income_color') : config('masjid.spending_color') }}">
                    {{ config('money.currency_code') }} {{ format_number($transactionTotal) }}
                </div>
            @else
                <div class="h1 mt-4" style="color: {{ config('masjid.income_color') }}">{{ config('money.currency_code') }} 0</div>
            @endif
        </div>
    </div>
</div>
