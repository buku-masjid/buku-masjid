<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
<div>
    {{ $transaction->description }}
    @can('update', $transaction)
        @can('manage-transactions', auth()->activeBook())
            {!! link_to_route(
                'categories.show',
                __('app.edit'),
                [$category->id, 'action' => 'edit', 'id' => $transaction->id] + request(['start_date', 'end_date', 'query', 'book_id']),
                ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'float-right text-danger']
            ) !!}
        @endcan
    @endcan
</div>
<div style="margin-bottom: 6px;">
    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
        {{ $transaction->bankAccount->name }}
    </span>
</div>
<hr style="margin: 6px 0">
