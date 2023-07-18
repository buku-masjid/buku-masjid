<span class="float-right">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'date' => $transaction->date_only,
    'month' => $month,
    'year' => $year,
    'category_id' => request('category_id'),
]) }}
<div>
    {{ $transaction->description }}
    @can('update', $transaction)
        {!! link_to_route(
            'transactions.index',
            __('app.edit'),
            ['action' => 'edit', 'id' => $transaction->id] + request(['month', 'year', 'query', 'category_id']),
            ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'float-right text-danger']
        ) !!}
    @endcan
</div>
<div style="margin-bottom: 6px;">
    @if ($transaction->category)
        @php
            $categoryRoute = route('categories.show', [
                $transaction->category_id,
                'start_date' => $startDate,
                'end_date' => $year.'-'.$month.'-'.date('t'),
            ]);
        @endphp
        <a href="{{ $categoryRoute }}">{!! optional($transaction->category)->name_label !!}</a>
    @endif
</div>
