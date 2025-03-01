<style>
.el {
  display: block;
  background-attachment: fixed;
  position: sticky;
  z-index: 1;
  left: 0px;
}
</style>
<div class="row pt-2 pt-sm-3 gap-2">
    <div class="col ps-0 ps-lg-3 pe-lg-0 el">
        <div class="card fw-bold p-3 mb-2 shadow border-0">
            {{ __('transaction.start_balance') }}<br>
            <span class="date">{{ $startDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-dark fw-bolder" id="lastMonthBalance">--</h1>
            <span class="date">{{ config('money.currency_code') }} {{ format_number($lastMonthBalance) }}</span>
            <i class="ti fe-normal bm-txt-primary bm-bg-primary-soft position-absolute top-50 end-0 translate-middle p-2">&#xeb75;</i>
        </div>
    </div>
    <div class="col px-0 el">
        <div class="card fw-bold p-3 mb-2 shadow border-0">
            {{ __('transaction.income') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-primary fw-bolder" id="currentMonthIncome">--</h1>
            <span class="date">{{ config('money.currency_code') }} {{ format_number($currentMonthIncome) }}</span>
            <i class="ti fe-bold bm-txt-primary bm-bg-primary-soft position-absolute top-50 end-0 translate-middle p-2">&#xea13;</i>
        </div>
    </div>
    <div class="col px-0 el">
        <div class="card fw-bold p-3 mb-2 shadow border-0">
            {{ __('transaction.spending') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-out fw-bolder" id="currentMonthSpending"></h1>
            <span class="date">{{ config('money.currency_code') }} {{ format_number($currentMonthSpending) }}</span>
            <i class="ti fe-bold bm-txt-out bm-bg-out-soft position-absolute top-50 end-0 translate-middle p-2">&#xea24;</i>
        </div>
    </div>
    <div class="col ps-0 el">
        <div class="card fw-bold p-3 mb-2 shadow border-0">
            {{ __('transaction.end_balance') }}<br>
            <span class="date">{{ $currentMonthEndDate->isoFormat('dddd, D MMMM Y') }}</span>
            <h1 class="pt-4 bm-txt-netral fw-bolder" id="endBalance">--</h1>
            <span class="date">{{ config('money.currency_code') }} {{ format_number($lastMonthBalance + $currentMonthBalance) }}</span>
            <i class="ti fe-normal bm-txt-netral bm-bg-netral-soft position-absolute top-50 end-0 translate-middle p-2">&#xeb75;</i>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/plugins/short-currency.js') }}"></script>
<script>
    var currencyCode = '{{ config('money.currency_code') }}';
    var localeCode = '{{ config('app.locale') }}';
    shortenMoneyContent('lastMonthBalance', parseInt({{$lastMonthBalance}}), localeCode, currencyCode);
    shortenMoneyContent('currentMonthIncome', parseInt({{$currentMonthIncome}}), localeCode, currencyCode);
    shortenMoneyContent('currentMonthSpending', parseInt({{$currentMonthSpending}}), localeCode, currencyCode);
    shortenMoneyContent('endBalance', parseInt({{$lastMonthBalance}} + {{$currentMonthBalance}}), localeCode, currencyCode);
</script>
@endpush
