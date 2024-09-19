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
                    <th>{{ __('transaction.transaction') }}</th>
                    <th class="text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topTransactionSummary as $key => $transaction)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ link_to_route('transactions.show', Illuminate\Support\Str::limit($transaction->description, 35, ''), $transaction) }}</td>
                        <td class="text-right" style="color: {{ config('masjid.'.$typeCode.'_color') }}">
                            {{ format_number($transaction->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
