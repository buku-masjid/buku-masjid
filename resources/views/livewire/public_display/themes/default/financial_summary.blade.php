<div class="w-full p-3 lg:p-0">
    <div class="lg:flex items-center">
        <div class="lg:w-1/5 font-bold text-center mb-3 lg:mb-0 border-r border-1 border-[#c0c4c5]">
            <span class="font-normal text-gray-600 text-2xl">Laporan</span><br>
            <h1 class="text-3xl bm-txt-primary">{{ $bookName }}</h1>
        </div>
        <div class="slider lg:w-4/5">
            <div class="slide-track">
                <div class="flex min-w-[100px] me-8 align-center items-center"></div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold">{{ __('transaction.start_balance') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">Per hari ini</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl start_week_balance_display"><span>--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($startWeekBalance) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold bm-txt-primary">{{ __('transaction.income') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">Hingga hari ini</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl current_week_income_total_display"><span>--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold text-red-900">{{ __('transaction.spending') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">{{ __('report.current_week_spending_total') }}</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl current_week_spending_total_display"><span>{{ $currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0 }}</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold text-sky-700">{{ __('transaction.end_balance') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">{{  $today->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl text-sky-700 current_balance_display"><span >--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
                    </div>
                </div>
                <!-- LOOP -->
                <div class="flex min-w-[100px] me-8 align-center items-center"></div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold">{{ __('transaction.start_balance') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">Per hari ini</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl start_week_balance_display"><span>--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($startWeekBalance) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold bm-txt-primary">{{ __('transaction.income') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">Hingga hari ini</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl current_week_income_total_display"><span>--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold text-red-900">{{ __('transaction.spending') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">{{ __('report.current_week_spending_total') }}</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl current_week_spending_total_display"><span>{{ $currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0 }}</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal) }}</span>
                    </div>
                </div>
                <div class="flex items-center align-center min-w-[400px] me-8">
                    <div class="flex-none">
                        <h1 class="text-2xl font-bold text-sky-700">{{ __('transaction.end_balance') }}</h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600" id="start_week_label">{{  $today->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <div class="px-8 flex-none"><span class="text-lg">→</span></div>
                    <div class="flex-1 w-2/5">
                        <h1 class="bm-txt-primary font-bold text-3xl text-sky-700 current_balance_display"><span >--</span></h1>
                        <span class="hidden lg:inline font-normal text-sm text-gray-600">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
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
    shortenMoneyContentByClassName('start_week_balance_display', parseInt({{$startWeekBalance}}), localeCode, currencyCode);
    shortenMoneyContentByClassName('current_balance_display', parseInt({{$currentBalance}}), localeCode, currencyCode);
    shortenMoneyContentByClassName('current_week_income_total_display', parseInt({{$currentWeekIncomeTotal}}), localeCode, currencyCode);
    shortenMoneyContentByClassName('current_week_spending_total_display', parseInt({{$currentWeekSpendingTotal}}), localeCode, currencyCode);
</script>
@endpush
