<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
{!! $transaction->date_alert !!}
<div>
    {!! nl2br(htmlentities($transaction->description)) !!}
    <span class="float-right">
        @can('update', $transaction)
            @can('manage-transactions', auth()->activeBook())
                {!! link_to_route(
                    'transactions.edit',
                    __('app.edit'),
                    [$transaction, 'reference_page' => 'partner', 'partner_id' => $partner->id] + request(['start_date', 'end_date', 'query']),
                    ['id' => 'edit-transaction-'.$transaction->id]
                ) !!} |
            @endcan
        @endcan
        {{ link_to_route('transactions.show', __('app.detail'), $transaction) }}
    </span>
</div>
<div style="margin-bottom: 6px;">
    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
        {{ $transaction->bankAccount->name }}
    </span>
</div>
<hr style="margin: 6px 0">
