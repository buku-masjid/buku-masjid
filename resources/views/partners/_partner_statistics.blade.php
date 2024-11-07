</div>

<div class="bg-white py-6 mb-5 mt-3 mt-sm-5">
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
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-lg" style="border-radius:1em;">
                    <div class="card-body p-6">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="h5 mb-2">{{ __('book.book') }}</div>
                                <div class="text-muted">Jumlah buku kas</div>
                            </div>
                            <div class="col-4"><div class="display-4 font-weight-bold text-orange">{{ $booksCount }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-lg" style="border-radius:1em;">
                    <div class="card-body p-3">
                        <div id="apexcharts_partner_level" style="max-width: 22em;margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-lg" style="border-radius:1em; height: 150px">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
