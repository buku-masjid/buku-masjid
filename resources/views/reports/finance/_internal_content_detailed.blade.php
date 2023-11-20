<table class="table table-sm mb-0 table-hover table-bordered">
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
        @foreach ($weekTransactions as $dayName => $daysTransactions)
            @if ($dayName)
                <tr><td class="text-center strong">{{ strtoupper($dayName) }}</td><td colspan="4">&nbsp;</td></tr>
            @endif
            @foreach ($daysTransactions as $transaction)
            <tr class="{{ $transaction->is_strong ? 'strong' : '' }}">
                <td class="text-center">{{ $transaction->date }}</td>
                <td {{ $transaction->is_strong ? 'style=text-decoration:underline' : '' }} class="{{ $transaction->is_strong ? 'strong' : '' }}">
                    {{ $transaction->description }}
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
            <td colspan="2" class="text-right strong">{{ __('app.total') }}</td>
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
            <td class="text-right strong">{{ format_number($incomeAmount - $spendingAmount) }}</td>
        </tr>
    </tfoot>
</table>
