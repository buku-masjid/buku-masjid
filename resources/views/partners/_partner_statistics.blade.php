</div>

<div class="bg-white py-6 mb-5 mt-3 mt-sm-5">
    <div class="container">
        <div class="row" style="min-height: 8em;">
            <div class="col-md-4 text-center text-md-left">
                <h1 class="page-title">{{ $selectedTypeName }}</h1>
                <div class="page-subtitle ml-0">
                    Berikut adalah data {{ __('transaction.in_out') }} {{ $selectedTypeName }} {{ Setting::get('masjid_name') }}.
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-sm-0 text-center">
                @livewire('partners.total-income-from-partner', ['partnerTypeCode' => $selectedTypeCode])
            </div>
            <div class="col-md-4 mt-3 mt-sm-0 text-center text-md-right">
                @livewire('partners.total-spending-to-partner', ['partnerTypeCode' => $selectedTypeCode])
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                @livewire('partners.books-count')
            </div>
            <div class="col-md-4">
                @livewire('partners.level-stats', ['partnerTypeCode' => $selectedTypeCode])
            </div>
            <div class="col-md-4">
                @livewire('partners.gender-stats', ['partnerTypeCode' => $selectedTypeCode])
            </div>
        </div>
        @livewire('partners.income-from-partner-graph', ['partnerTypeCode' => $selectedTypeCode])
        @if ($partnerMonthlySpendingSeries)
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="h2" style="color: {{ config('masjid.spending_color') }}">{{ __('transaction.spending') }}</div>
                    <div id="apexcharts_partner_monthly_spending" style="margin: 0 auto;"></div>
                </div>
            </div>
            @push('scripts')
                <script>
                    var options = {
                        series: {!! json_encode(array_values($partnerMonthlySpendingSeries)) !!},
                        chart: {
                            height: 350,
                            type: 'line',
                            zoom: {
                                enabled: false
                            }
                        },
                        labels: {!! json_encode(array_values(get_months())) !!},
                        stroke: {
                            width: 4,
                            curve: 'smooth'
                        },
                    };
                    var apexcharts_partner_monthly_spending = new ApexCharts(document.querySelector("#apexcharts_partner_monthly_spending"), options);
                    apexcharts_partner_monthly_spending.render();
                </script>
            @endpush
        @endif
    </div>
</div>
<div class="container">
