<div class="card shadow-lg" wire:init="getDonorsCount" style="border-radius:1em; height: 10em">
    <div class="card-body p-3 row align-items-center" >
        @if ($isLoading)
            <div class="loading-state text-center w-100">
                <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
            </div>
        @else
            <table class="table-sm w-100">
                <tr>
                    <td>{{ __('donor.donors_total') }}</td>
                    <td class="text-center h3 text-orange">{{ $donorsCount['total'] }}</td>
                </tr>
                <tr>
                    <td>{{ __('donor.donors_total') }} {{ $month == '00' ? __('report.this_year') : __('report.this_month') }}</td>
                    <td class="text-center h3 text-orange">{{ $donorsCount['current_periode_total'] }}</td>
                </tr>
                <tr>
                    <td>{{ __('donor.donors_total') }} {{ $month == '00' ? __('report.last_year') : __('report.last_month') }}</td>
                    <td class="text-center h3 text-orange">{{ $donorsCount['last_periode_total'] }}</td>
                </tr>
            </table>
        @endif
    </div>
</div>
