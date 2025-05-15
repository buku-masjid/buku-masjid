<div class="col-lg-12">
    <div class="row align-items-center">
        <div class="col-2 fw-bold lh-1 ps-4 text-center report-header">
            <span class="fw-normal text-secondary">Laporan </span><br>
            <h1>{{ $bookName }}</h1>
        </div>
        <div class="col slider">
            <div class="slide-track" id="slide-track">
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-3">
                            <h1 class="lh-1">{{ __('transaction.start_balance') }}</h1>
                            <span class="fw-normal sum-info" id="start_week_label">{{ __('report.balance_per_date', ['date' => $startWeek->isoFormat('dddd, D MMMM Y')]) }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-primary fw-bolder lh-1"><span id="start_week_balance_display">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="start_week_balance">{{ config('money.currency_code') }} {{ format_number($startWeekBalance) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.income') }}<br></h1>
                            <span class="sum-info fw-normal " id="start_week_label">{{ __('report.current_week_income_total') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5 text-start">
                            <h1 class="bm-txt-primary fw-bolder lh-1"><span id="current_week_income_total_display">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_income_total">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.spending') }}</h1>
                            <span class="sum-info fw-normal " id="start_week_label">{{ __('report.current_week_spending_total') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-out fw-bolder lh-1"><span id="current_week_spending_total_display">{{ $currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0 }}</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.end_balance') }}</h1>
                            <span class="sum-info fw-normal" id="start_week_label">{{  $today->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-netral fw-bolder lh-1"><span id="current_balance_display">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
                        </div>
                    </div>
                </div>
                <!-- LOOP HERE -->
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-3">
                            <h1 class="lh-1">{{ __('transaction.start_balance') }}</h1>
                            <span class="fw-normal sum-info" id="start_week_label">{{ __('report.balance_per_date', ['date' => $startWeek->isoFormat('dddd, D MMMM Y')]) }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-primary fw-bolder lh-1"><span id="start_week_balance_display2">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="start_week_balance">{{ config('money.currency_code') }} {{ format_number($startWeekBalance) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.income') }}<br></h1>
                            <span class="sum-info fw-normal " id="start_week_label">{{ __('report.current_week_income_total') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5 text-start">
                            <h1 class="bm-txt-primary fw-bolder lh-1"><span id="current_week_income_total_display">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_income_total">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.spending') }}</h1>
                            <span class="sum-info fw-normal " id="start_week_label">{{ __('report.current_week_spending_total') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-out fw-bolder lh-1"><span id="current_week_spending_total_display">{{ $currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0 }}</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal) }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="fw-bold row p-3 lh-1 align-items-center">
                        <div class="col-auto pe-5">
                            <h1 class="lh-1">{{ __('transaction.end_balance') }}</h1>
                            <span class="sum-info fw-normal" id="start_week_label">{{  $today->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>
                        <div class="col-auto"><h1 class="ti">&#xea1f;</h1></div>
                        <div class="col ps-5">
                            <h1 class="bm-txt-netral fw-bolder lh-1"><span id="current_balance_display">--</span></h1>
                            <span class="sum-info fw-normal text-secondary" id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/plugins/short-currency.js') }}"></script>
<script>
    var currencyCode = '{{ config('money.currency_code') }}';
    var localeCode = '{{ config('app.locale') }}';
    shortenMoneyContent('start_week_balance_display', parseInt({{$startWeekBalance}}), localeCode, currencyCode);
    shortenMoneyContent('start_week_balance_display2', parseInt({{$startWeekBalance}}), localeCode, currencyCode);
    shortenMoneyContent('current_balance_display', parseInt({{$currentBalance}}), localeCode, currencyCode);
    shortenMoneyContent('current_week_income_total_display', parseInt({{$currentWeekIncomeTotal}}), localeCode, currencyCode);
    shortenMoneyContent('current_week_spending_total_display', parseInt({{$currentWeekSpendingTotal}}), localeCode, currencyCode);
</script>
@endpush 