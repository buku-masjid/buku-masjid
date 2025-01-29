<div class="row pt-2 pt-sm-3 gap-2">
    <div class="col pe-0">
        <div class="card fw-bold p-3 mb-2 shadow">
            {{ __('transaction.start_balance') }}<br>
            <span class="date">{{ $startDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-dark fw-bolder">{{ format_number($lastMonthBalance) }}</h1>
            <i class="ti fe-normal bm-txt-dark bm-bg-primary-soft position-absolute top-50 end-0 translate-middle p-2">&#xeb75;</i>
        </div>
    </div>
    <div class="col px-0">
        <div class="card fw-bold p-3 mb-2 shadow">
            {{ __('transaction.income') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-primary fw-bolder">{{ format_number($currentMonthIncome) }}</h1>
            <i class="ti fe-bold bm-txt-primary bm-bg-primary-soft position-absolute top-50 end-0 translate-middle p-2">&#xea13;</i>
        </div>
    </div>
    <div class="col px-0">
        <div class="card fw-bold p-3 mb-2 shadow">
            {{ __('transaction.spending') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-out fw-bolder">{{ format_number($currentMonthSpending) }}</h1>
            <i class="ti fe-bold bm-txt-out bm-bg-out-soft position-absolute top-50 end-0 translate-middle p-2">&#xea24;</i>
        </div>
    </div>
    <div class="col ps-0">
        <div class="card fw-bold p-3 mb-2 shadow">
            {{ __('transaction.end_balance') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-netral fw-bolder">
                {{ format_number($lastMonthBalance + $currentMonthBalance) }}
            </h1>
            <i class="ti fe-normal bm-txt-netral bm-bg-netral-soft position-absolute top-50 end-0 translate-middle p-2">&#xeb75;</i>
        </div>
    </div>
</div>
