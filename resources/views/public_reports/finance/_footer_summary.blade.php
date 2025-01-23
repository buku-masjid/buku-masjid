<div class="row fs-3 py-3 px-lg-4 py-lg-3">
    <div class="col text-center text-sm-start fs-2 fw-bold py-3">{{ __('report.balance_per_date', ['date' => $currentMonthEndDate->isoFormat('D MMMM Y')]) }}</div>
    <div class="col-sm-auto text-center pb-2 pb-sm-0 text-sm-end">
        {{ __('transaction.income') }}
        <h2 class="bm-txt-primary ">{{ format_number($currentMonthIncome) }}</h2>
    </div>
    <div class="col-sm-auto text-center pt-3 pt-sm-0  text-sm-end">
        {{ __('transaction.spending') }}
        <h2 class="bm-txt-out ">{{ format_number($currentMonthSpending) }}</h2>
    </div>
</div>
<div class="fs-3 fw-bold text-center text-sm-end border-top pt-3 px-lg-4">
    {{ __('transaction.end_balance') }}
    <h1 class="bm-txt-netral fw-bolder">{{ format_number($currentMonthBalance) }}</h1>
</div>
