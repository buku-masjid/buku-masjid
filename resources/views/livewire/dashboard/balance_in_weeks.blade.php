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
                    <th class="text-right">{{ __('time.date') }}</th>
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
                @php
                    $no = 1;
                @endphp
                @foreach ($balanceInWeekSummary as $weekNumber => $balanceSummary)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-right text-nowrap">
                            @if ($isForPrint)
                                {{ $balanceSummary->date_range_text }}
                            @else
                                {{ link_to_route('reports.finance.dashboard', $balanceSummary->date_range_text, [
                                    'start_date' => $balanceSummary->start_date,
                                    'end_date' => $balanceSummary->end_date,
                                ]) }}
                            @endif
                        </td>
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.income_color') }}">{{ format_number($balanceSummary->income) }}</td>
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.spending_color') }}">{{ format_number($balanceSummary->spending) }}</td>
                        @php
                            $typeCode = $balanceSummary->balance >= 0 ? 'income' : 'spending';
                        @endphp
                        <td class="text-right text-nowrap" style="color: {{ config('masjid.'.$typeCode.'_color') }}">
                            {{ format_number($balanceSummary->balance) }}
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
