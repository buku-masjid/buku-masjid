<h5 class="text-center mb-0">{{ $transaction->day_name }}</h5>
<span class="float-right">{{ $transaction->amount_string }}</span>
{!! $transaction->date_alert !!}
{{ link_to_route('transactions.index', $transaction->date, [
    'query' => $searchQuery,
    'date' => $transaction->date_only,
    'month' => $transaction->month,
    'year' => $transaction->year,
]) }}
<div>
    {!! nl2br(htmlentities($transaction->description)) !!}
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
    @if ($transaction->category)
        @php
            $categoryRoute = route('categories.show', [
                $transaction->category_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        @endphp
        <a href="{{ $categoryRoute }}">{!! $transaction->category->name_label !!}</a>
    @endif
</div>
<hr class="my-2">
