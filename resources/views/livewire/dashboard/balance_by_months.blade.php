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
                    <th colspan="2" class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('income')) }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('spending')) }}</th>
                    <th class="text-right">{{ format_number($balanceByMonthSummary->sum('balance')) }}</th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
