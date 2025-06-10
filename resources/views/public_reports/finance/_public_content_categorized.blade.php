<table class="table table-sm table-hover mb-0">
    <thead>
        <tr >
            <th style="width: 5%" class="text-center ">{{ __('app.table_no') }}</th>
            <th style="width: 15%" class="text-center ">{{ __('time.date') }}</th>
            <th style="width: 60%">{{ __('app.description') }}</th>
            <th style="width: 20%" class="text-nowrap text-end ">{{ __('transaction.amount') }}</th>
        </tr>
    </thead>
    @if ($hasGroupedTransactions)
    @if ($isForInternal)
    @else
        <tbody>
            @php
                $key = 0;
            @endphp
            @foreach ($transactions as $transaction)
            <tr>
                <td class="text-center col-1">{{ ++$key }}</td>
                <td class="text-center col-2">{{ $transaction->date }}</td>
                <td class="col-4">
                    @if ($isTransactionFilesVisible)
                        <span class="float-end">
                            @livewire('public-books.files-indicator', ['transaction' => $transaction])
                        </span>
                    @endif
                    {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                </td>
                <td class="text-end col-3">{{ format_number($transaction->amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif
    <tfoot>
        <tr class="strong">
            <td colspan="3" class="text-end">{{ __('app.total') }} {{ $categoryName }}</td>
            <td class="text-end">
                {{ format_number($transactions->sum('amount')) }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>
