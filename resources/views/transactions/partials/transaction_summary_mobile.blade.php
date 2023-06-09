@if (request('category_id') || request('partner_id'))
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.income_total') }}</div>
        <div class="col-6 text-right strong">{{ format_number($incomeTotal) }}</div>
    </div>
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.spending_total') }}</div>
        <div class="col-6 text-right strong">{{ format_number($spendingTotal) }}</div>
    </div>
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.end_balance') }}</div>
        <div class="col-6 text-right strong">{{ number_format($incomeTotal - $spendingTotal, 2) }}</div>
    </div>
@else
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.start_balance') }}</div>
        <div class="col-6 text-right strong">
            @php
                $balance = 0;
            @endphp
            @if ($transactions->last())
                {{ format_number($balance = balance(Carbon\Carbon::parse($transactions->last()->date)->subDay()->format('Y-m-d'))) }}
            @else
                0
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.income_total') }}</div>
        <div class="col-6 text-right strong">{{ format_number($incomeTotal) }}</div>
    </div>
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.spending_total') }}</div>
        <div class="col-6 text-right strong">{{ format_number($spendingTotal) }}</div>
    </div>
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.end_balance') }}</div>
        <div class="col-6 text-right strong">
            @if ($transactions->first())
                {{ format_number($balance + $incomeTotal - $spendingTotal) }}
            @else
                0
            @endif
        </div>
    </div>
@endif
