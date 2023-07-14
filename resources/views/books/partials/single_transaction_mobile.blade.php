<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
<div>
    {{ $transaction->description }}
    @can('update', $transaction)
        {!! link_to_route(
            'books.show',
            __('app.edit'),
            [$book->id, 'action' => 'edit', 'id' => $transaction->id] + request(['start_date', 'end_date', 'query', 'category_id']),
            ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'float-right text-danger']
        ) !!}
    @endcan
</div>
<div>{!! optional($transaction->category)->name_label !!}</div>
<hr style="margin: 6px 0">
