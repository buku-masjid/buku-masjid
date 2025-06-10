<div wire:init="getTopTransactionSummary">
    @if ($isLoading)
        <div class="loading-state text-center">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>{{ __('app.table_no') }}</th>
                    <th style="width: 70%" class="text-left">{{ __('transaction.transaction') }}</th>
                    <th style="width: 25%" class="text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topTransactionSummary as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ ++$key }}</td>
                        <td>
                            @if ($isForPrint)
                                {!! nl2br(htmlentities($transaction->description)) !!}
                            @else
                                {!! $transaction->date_alert !!}
                                {{ link_to_route('transactions.show', Illuminate\Support\Str::limit($transaction->description, 35, ''), $transaction) }}</td>
                            @endif
                        <td class="text-right" style="color: {{ config('masjid.'.$typeCode.'_color') }}">
                            {{ format_number($transaction->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
