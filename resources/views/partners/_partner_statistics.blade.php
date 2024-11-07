</div>

<div class="bg-white py-6 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h1 class="page-title">{{ $selectedTypeName }}</h1>
                <div class="page-subtitle ml-0">
                    Berikut adalah data pemasukan/pengeluaran {{ $selectedTypeName }} {{ Setting::get('masjid_name') }}.
                </div>
            </div>
            <div class="col-md-4 text-center">
                @if ($partnerTotalIncome && $partnerTotalSpending)
                    <div>
                        <div class="lead">{{ __('report.current_week_income_total') }}</div>
                        <div class="text-muted my-1">{{ __('app.update') }}: {{ today()->isoFormat('dddd, DD MMM YYYY') }}</div>
                        <div class="h1" style="color: {{ config('masjid.income_color') }}">
                            {{ config('money.currency_code') }} {{ format_number($partnerTotalIncome) }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4 text-right">
                @if ($partnerTotalIncome && !$partnerTotalSpending)
                    <div>
                        <div class="lead">{{ __('report.current_week_income_total') }}</div>
                        <div class="text-muted my-1">{{ __('app.update') }}: {{ today()->isoFormat('dddd, DD MMM YYYY') }}</div>
                        <div class="h1" style="color: {{ config('masjid.income_color') }}">
                            {{ config('money.currency_code') }} {{ format_number($partnerTotalIncome) }}
                        </div>
                    </div>
                @endif
                @if ($partnerTotalSpending)
                    <div>
                        <div class="lead">{{ __('report.current_week_spending_total') }}</div>
                        <div class="text-muted my-1">{{ __('app.update') }}: {{ today()->isoFormat('dddd, DD MMM YYYY') }}</div>
                        <div class="h1" style="color: {{ config('masjid.spending_color') }}">
                            {{ config('money.currency_code') }} {{ format_number($partnerTotalSpending) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="container">
