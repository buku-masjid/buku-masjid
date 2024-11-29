<div class="card shadow-lg align-item-center" wire:init="getLevelStats" style="border-radius:1em; height: 10em">
    <div class="card-body py-2 row align-items-center" >
        @if ($isLoading)
            <div class="loading-state text-center w-100">
                <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
            </div>
        @else
            <div id="apex_partner_level_stats" style="width: 22em;margin: 0 auto;"></div>
            <script>
                var options = {
                    series: {!! json_encode(array_values($partnerLevelStats)) !!},
                    colors: ['#C0C0C0', '#FFD700', '#a0b2c6'],
                    chart: {
                        type: 'donut',
                    },
                    labels: {!! json_encode(array_keys($partnerLevelStats)) !!},
                    dataLabels: {
                        enabled: false,
                    }
                };
                var apex_partner_level_stats = new ApexCharts(document.querySelector("#apex_partner_level_stats"), options);
                apex_partner_level_stats.render();
            </script>
        @endif
    </div>
</div>
