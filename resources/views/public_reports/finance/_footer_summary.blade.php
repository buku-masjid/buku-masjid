<div class="fs-3 fw-bold text-center text-sm-end py-3 px-lg-4">
    {{ __('transaction.start_balance') }}
    <h1 class="bm-txt-dark fw-bolder">{{ config('money.currency_code') }}{{ format_number($lastMonthBalance) }}</h1>
</div>
<div class="py-3 border-top border-bottom">
    <div class="col text-center fs-4 fw-bold pb-3">
        {{ __('report.balance_per_date', ['date' => $currentMonthEndDate->isoFormat('D MMMM Y')]) }}
    </div>
    <div class="row fs-3 px-lg-4">
        <div class="col-sm text-center text-secondary pb-2 pb-sm-0">
            {{ __('transaction.income') }}
            <h2 class="bm-txt-primary ">{{ config('money.currency_code') }}{{ format_number($currentMonthIncome) }}</h2>
        </div>
        <div class="col-sm text-center text-secondary pt-3 pt-sm-0">
            {{ __('transaction.spending') }}
            <h2 class="bm-txt-out ">{{ config('money.currency_code') }}{{ format_number($currentMonthSpending) }}</h2>
        </div>
        <div class="col-sm text-center text-secondary pt-3 pt-sm-0">
            {{ __('transaction.balance') }}
            <h2 class="bm-txt-dark ">{{ config('money.currency_code') }}{{ format_number($currentMonthBalance) }}</h2>
        </div>
    </div>
</div>
<div class="fs-3 fw-bold text-center text-sm-end pt-4 px-lg-4">
    {{ __('transaction.end_balance') }}
    <h1 class="bm-txt-netral fw-bolder">{{ config('money.currency_code') }}{{ format_number($lastMonthBalance + $currentMonthBalance) }}</h1>
</div>
