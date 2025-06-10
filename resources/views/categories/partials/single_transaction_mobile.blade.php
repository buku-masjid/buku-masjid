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
                    [$transaction, 'reference_page' => 'category', 'category_id' => $category->id] + request(['start_date', 'end_date', 'query', 'book_id']),
                    ['id' => 'edit-transaction-'.$transaction->id]
                ) !!} |
            @endcan
        @endcan
        {{ link_to_route('transactions.show', __('app.detail'), $transaction) }}
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
