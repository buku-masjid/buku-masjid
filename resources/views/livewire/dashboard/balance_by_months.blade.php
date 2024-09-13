<div wire:init="getBalanceByMonthsSummary">
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
                @foreach ($balanceByMonthSummary as $monthNumber => $balanceSummary)
                    <tr>
                        <td>{{ $monthNumber }}</td>
                        <td>{{ $balanceSummary['month_name'] }}</td>
                        <td class="text-right">{{ format_number($balanceSummary['income']) }}</td>
                        <td class="text-right">{{ format_number($balanceSummary['spending']) }}</td>
                        <td class="text-right">{{ format_number($balanceSummary['balance']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>&nbsp;</th>
                    <th>{{ __('app.total') }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('income')) }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('spending')) }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('balance')) }}</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th colspan="3">{{ __('transaction.end_balance') }}</th>
                    <th class="text-right">{{ format_number($startingBalance + $balanceByMonthSummary->sum('balance')) }}</th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
