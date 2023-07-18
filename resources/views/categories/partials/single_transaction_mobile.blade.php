<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
<div>
    {{ $transaction->description }}
    @can('update', $transaction)
        {!! link_to_route(
            'categories.show',
            __('app.edit'),
            [$category->id, 'action' => 'edit', 'id' => $transaction->id] + request(['start_date', 'end_date', 'query', 'book_id']),
            ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'float-right text-danger']
        ) !!}
    @endcan
</div>
<hr style="margin: 6px 0">
