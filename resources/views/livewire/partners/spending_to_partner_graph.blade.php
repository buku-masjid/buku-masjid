<div class="row justify-content-center" wire:init="getSpendingToPartnerSeries">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        @if ($spendingToPartnerSeries)
            <div class="col-md-10">
                <div class="h2" style="color: {{ config('masjid.spending_color') }}">
                    {{ __('transaction.spending') }}
                    <span class="h6 text-muted">{{ __('report.in_thousand') }} {{ config('money.currency_text') }}</span>
                </div>
                <div id="apexcharts_spending_to_partner" style="margin: 0 auto;"></div>
                <script>
                    var options = {
                        series: {!! json_encode(array_values($spendingToPartnerSeries)) !!},
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
                    var apexcharts_spending_to_partner = new ApexCharts(document.querySelector("#apexcharts_spending_to_partner"), options);
                    apexcharts_spending_to_partner.render();
                </script>
            </div>
        @endif
    @endif
</div>
