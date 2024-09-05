<table class="table table-sm table-hover mb-0">
    <thead>
        <tr >
            <th style="width: 5%" class="text-center ">{{ __('app.table_no') }}</th>
            <th style="width: 15%" class="text-center ">{{ __('time.date') }}</th>
            <th style="width: 60%">{{ __('app.description') }}</th>
            <th style="width: 20%" class="text-nowrap text-right ">{{ __('transaction.amount') }}</th>
        </tr>
    </thead>
    @if ($hasGroupedTransactions)
    <tbody>
        @php
            $key = 0;
        @endphp

        @switch(request('order'))
            @case('desc')
                @php $transactions = collect($transactions)->sortByDesc('amount'); @endphp
                @break
            @case('asc')
                @php $transactions = collect($transactions)->sortBy('amount'); @endphp
                @break
            @default
                @php $transactions = collect($transactions); @endphp
        @endswitch

        @foreach ($transactions as $transaction)
        <tr>
            <td class="text-center ">{{ ++$key }}</td>
            <td class="text-center ">{{ $transaction->date }}</td>
            <td>{{ $transaction->description }}</td>
            <td class="text-right ">{{ format_number($transaction->amount) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right strong">{{ __('app.total') }} {{ $categoryName }}</td>
            <td class="text-right strong">
                {{ format_number($transactions->sum('amount')) }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>
