<div class="row justify-content-center" wire:init="getIncomeFromPartnerSeries">
    @if ($isLoading)
        <div class="loading-state text-center w-100 py-6">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        @if ($incomeFromPartnerSeries)
            <div class="col-md-10">
                <div class="h2" style="color: {{ config('masjid.income_color') }}">
                    {{ __('transaction.income') }}
                    <span class="h6 text-muted">{{ __('report.in_thousand') }} {{ config('money.currency_text') }}</span>
                </div>
                <div id="apexcharts_income_from_partner" style="margin: 0 auto;"></div>
                <script>
                    var options = {
                        colors: ['#4ECDC4','#C7F464','#81D4FA','#546E7A','#FD6A6A'],
                        series: {!! json_encode(array_values($incomeFromPartnerSeries)) !!},
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
                        yaxis: {
                            labels: {
                                formatter: function (value) {
                                    return parseFloat((value/1000).toFixed(2)).toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            y: {
                                title: {
                                    formatter: (seriesName) => '{{ __('time.year') }} ' + seriesName,
                                },
                            },
                        },
                    };
                    var apexcharts_income_from_partner = new ApexCharts(document.querySelector("#apexcharts_income_from_partner"), options);
                    apexcharts_income_from_partner.render();
                </script>
            </div>
        @endif
    @endif
</div>
