<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
<div>
    {{ $transaction->description }}
    <span class="float-right">
        @can('update', $transaction)
            @can('manage-transactions', auth()->activeBook())
                {!! link_to_route(
                    'categories.show',
                    __('app.edit'),
                    [$category->id, 'action' => 'edit', 'id' => $transaction->id] + request(['start_date', 'end_date', 'query', 'book_id']),
                    ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'text-danger']
                ) !!} |
            @endcan
        @endcan
        {{ link_to_route('transactions.show', __('app.show'), $transaction) }}
    </span>
</div>
<div style="margin-bottom: 6px;">
    @if ($transaction->partner)
        @php
            $partnerRoute = route('partners.show', [
                $transaction->partner_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        @endphp
        <a class="badge badge-info" href="{{ $partnerRoute }}">{{ $transaction->partner->name }}</a>
    @endif
    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
        {{ $transaction->bankAccount->name }}
    </span>
</div>
<hr style="margin: 6px 0">
