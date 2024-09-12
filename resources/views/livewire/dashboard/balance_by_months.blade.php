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
                @foreach (get_months() as $monthNumber => $monthName)
                    <tr>
                        <td>{{ $monthNumber }}</td>
                        <td>{{ $monthName }}</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">0</th>
                    <th class="text-right">0</th>
                    <th class="text-right">0</th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
