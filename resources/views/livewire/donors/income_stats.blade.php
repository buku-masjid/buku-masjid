<div class="card shadow-lg align-item-center" wire:init="getIncomeStats" style="border-radius:1em; height: 10em">
    <div class="card-body py-2 row align-items-center" >
        @if ($isLoading)
            <div class="loading-state text-center w-100">
                <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
            </div>
        @else
            <table class="table-sm w-100">
                <tr>
                    <td>
                        {{ __('transaction.income') }} {{ $month == '00' ? __('report.last_year') : __('report.last_month') }}
                        <div class="text-muted">({{ $partnerIncomeStats['last_month_name'] }})</div>
                    </td>
                    <td class="text-right h4 align-top" style="color: {{ config('masjid.income_color') }}">
                        {{ config('money.currency_code') }} {{ format_number($partnerIncomeStats['last_month_total']) }}
                    </td>
                </tr>
                @if ($month != '00')
                    <tr>
                        <td>
                            {{ __('transaction.income') }} {{ __('report.this_year') }}
                            <div class="text-muted">({{ $partnerIncomeStats['current_year_name'] }})</div>
                        </td>
                        <td class="text-right h4 align-top" style="color: {{ config('masjid.income_color') }}">
                            {{ config('money.currency_code') }} {{ format_number($partnerIncomeStats['current_year_total']) }}
                        </td>
                    </tr>
                @endif
            </table>
        @endif
    </div>
</div>
