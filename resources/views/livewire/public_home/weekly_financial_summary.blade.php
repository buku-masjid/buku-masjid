<style>
.el {
  display: block;
  background-attachment: fixed;
  position: sticky;
  z-index: 1;
  top: 0
}
</style>
<div class="col-lg-12">
    <div class="fs-4 pt-3 pb-3 d-flex align-items-center">
        <span class="fs-2 fw-bold pe-2">{{ __('report.summary_weekly') }}</span>
        <span class="badge bg-cyan-lt">{{ $bookName }}</span>
    </div>
    <div class="row align-items-end">
        <div class="col-lg ps-sm-0 el">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('transaction.start_balance') }}<br>
                <span class="date" id="start_week_label">{{ __('report.balance_per_date', ['date' => $startWeek->isoFormat('dddd, D MMMM Y')]) }}</span>
                <h1 class="pt-4 bm-txt-primary fw-bolder"><span id="start_week_balance_display">--</span></h1>
                <span class="date" id="start_week_balance">{{ config('money.currency_code') }} {{ format_number($startWeekBalance) }}</span>
            </div>
        </div>
        <div class="col-lg ps-sm-0 el">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('transaction.income') }}<br>
                <span class="date">{{ __('report.current_week_income_total') }}</span>
                <h1 class="pt-4 bm-txt-primary fw-bolder">
                    <span id="current_week_income_total_display">--</span>
                </h1>
                <span class="date" id="current_week_income_total">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
            </div>
        </div>
        <div class="col-lg ps-sm-0 el">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('transaction.spending') }}<br>
                <span class="date">{{ __('report.current_week_spending_total') }}</span>
                <h1 class="pt-4 bm-txt-out fw-bolder">
                     <span id="current_week_spending_total_display">{{ $currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0 }}</span>
                </h1>
                <span class="date" id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal) }}</span>
            </div>
        </div>
        <div class="col-lg ps-sm-0 el">
            <div class="card fs-3 fw-bold p-3 mb-2 position-relative shadow-lg">
                @if ($bookVisibility == 'public')
                    <a class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 me-3 px-2 py-1" href="{{ route('public_reports.index') }}" role="button">{{ __('app.show') }}</a>
                @endif
                {{ __('transaction.end_balance') }}<br>
                <span class="date" id="current_balance_label">{{ __('report.today_balance', ['date' => $today->isoFormat('dddd, D MMMM Y')]) }}</span>
                <h1 class="pt-4 bm-txt-netral fw-bolder">
                    <span id="current_balance_display">--</span>
                </h1>
                <span class="date" id="current_balance">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
            </div>
        </div>
    </div>
    <a class="d-sm-none btn bm-btn btn-sm btn-ghost-cyan mt-2" href="{{ route('public_reports.index') }}" role="button">
        {{ __('report.view_report') }}<i class="ti">&#xea1c;</i>
    </a>
</div>

@push('scripts')
<script src="{{ asset('js/plugins/short-currency.js') }}"></script>
<script>
    var currencyCode = '{{ config('money.currency_code') }}';
    var localeCode = '{{ config('app.locale') }}';
    shortenMoneyContent('start_week_balance_display', parseInt({{$startWeekBalance}}), localeCode, currencyCode);
    shortenMoneyContent('current_balance_display', parseInt({{$currentBalance}}), localeCode, currencyCode);
    shortenMoneyContent('current_week_income_total_display', parseInt({{$currentWeekIncomeTotal}}), localeCode, currencyCode);
    shortenMoneyContent('current_week_spending_total_display', parseInt({{$currentWeekSpendingTotal}}), localeCode, currencyCode);
</script>
@endpush


