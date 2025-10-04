@if (request('category_id') || request('book_id'))
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
        <div class="col-6 text-right strong">{{ format_number($incomeTotal - $spendingTotal) }}</div>
    </div>
@else
    <div class="row">
        <div class="col-6 text-right strong">{{ __('transaction.start_balance') }}</div>
        <div class="col-6 text-right strong">
            {{ format_number($balance = auth()->activeBook()->getBalance(Carbon\Carbon::parse($startDate)->subDay()->format('Y-m-d'))) }}
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
            {{ format_number($balance + $incomeTotal - $spendingTotal) }}
        </div>
    </div>
@endif
