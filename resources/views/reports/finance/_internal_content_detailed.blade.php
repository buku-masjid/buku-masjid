<table class="table table-sm mb-0 card-table table-hover table-bordered">
    <thead>
        <tr>
            <th class="text-center">{{ __('app.date') }}</th>
            <th>{{ __('transaction.transaction') }}</th>
            <th class="text-right">{{ __('transaction.income') }}</th>
            <th class="text-right">{{ __('transaction.spending') }}</th>
            <th class="text-right">{{ __('transaction.balance') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="strong">{{ 'Sisa saldo per '.$lastWeekDate->isoFormat('D MMMM Y') }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="strong text-right text-nowrap">{{ format_number($currentWeekBalance = auth()->activeBook()->getBalance($lastWeekDate->format('Y-m-d'))) }}</td>
        </tr>
        @foreach ($weekTransactions as $dayName => $daysTransactions)
            @if ($dayName)
                <tr><td class="text-center strong">{{ strtoupper($dayName) }}</td><td colspan="4">&nbsp;</td></tr>
            @endif
            @foreach ($daysTransactions as $transaction)
            <tr class="{{ $transaction->is_strong ? 'strong' : '' }}">
                <td class="text-center text-nowrap">{{ Carbon\Carbon::parse($transaction->date)->isoFormat('DD MMM YYYY') }}</td>
                <td {{ $transaction->is_strong ? 'style=text-decoration:underline' : '' }} class="{{ $transaction->is_strong ? 'strong' : '' }}">
                    {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                </td>
                <td class="text-right {{ $transaction->is_strong ? 'strong' : '' }}">{{ $transaction->in_out ? format_number($transaction->amount) : '' }}</td>
                <td class="text-right {{ $transaction->is_strong ? 'strong' : '' }}">{{ !$transaction->in_out ? format_number($transaction->amount) : '' }}</td>
                <td class="text-center {{ $transaction->is_strong ? 'strong' : '' }}">&nbsp;</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-right strong">{{ __('app.total') }} {{ __('time.week') }} {{ $weekNumber + 1 }}</td>
            <td class="text-right strong">
                @php
                    $incomeAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? $transaction->amount : 0;
                    });
                @endphp
                {{ format_number($incomeAmount) }}
            </td>
            <td class="text-right strong">
                @php
                    $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? 0 : $transaction->amount;
                    });
                @endphp
                {{ format_number($spendingAmount) }}
            </td>
            <td class="text-right strong text-nowrap">{{ format_number($incomeAmount - $spendingAmount) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="text-right strong">{{ __('transaction.end_balance') }} {{ __('time.week') }} {{ $weekNumber + 1 }}</td>
            <td class="text-right strong">&nbsp;</td>
            <td class="text-right strong">&nbsp;</td>
            <td class="text-right strong text-nowrap">{{ format_number($currentWeekBalance + $incomeAmount - $spendingAmount) }}</td>
        </tr>
    </tfoot>
</table>
