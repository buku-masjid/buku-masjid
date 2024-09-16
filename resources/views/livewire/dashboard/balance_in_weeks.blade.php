<div wire:init="getBalanceInWeeksSummary">
    @if ($isLoading)
        <div class="loading-state text-center">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>{{ __('app.table_no') }}</th>
                    <th>{{ __('time.month') }}</th>
                    <th class="text-right">{{ __('transaction.income') }}</th>
                    <th class="text-right">{{ __('transaction.spending') }}</th>
                    <th class="text-right">{{ __('transaction.balance') }}</th>
                </tr>
                <tr class="">
                    <th>&nbsp;</th>
                    <th colspan="3">{{ __('transaction.start_balance') }}</th>
                    <th class="text-right">{{ format_number($startingBalance) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($balanceInWeekSummary as $monthNumber => $balanceSummary)
                    <tr>
                        <td>{{ $monthNumber }}</td>
                        <td>
                            {{ link_to_route('transactions.index', $balanceSummary['month_name'], [
                                'month' => $monthNumber,
                                'year' => $year,
                            ]) }}
                        </td>
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.income_color') }}">{{ format_number($balanceSummary['income']) }}</td>
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.spending_color') }}">{{ format_number($balanceSummary['spending']) }}</td>
                        @php
                            $typeCode = $balanceSummary['balance'] >= 0 ? 'income' : 'spending';
                        @endphp
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.'.$typeCode.'_color') }}">
                            {{ format_number($balanceSummary['balance']) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if ($balanceInWeekSummary->count() > 1)
                    <tr>
                        <th>&nbsp;</th>
                        <th>{{ __('app.total') }}</th>
                        <th class="text-right">{{ format_number($balanceInWeekSummary->sum('income')) }}</th>
                        <th class="text-right">{{ format_number($balanceInWeekSummary->sum('spending')) }}</th>
                        <th class="text-right">{{ format_number($balanceInWeekSummary->sum('balance')) }}</th>
                    </tr>
                @endif
                <tr>
                    <th>&nbsp;</th>
                    <th colspan="3">{{ __('transaction.end_balance') }}</th>
                    <th class="text-right">{{ format_number($startingBalance + $balanceInWeekSummary->sum('balance')) }}</th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
