<span class="float-right">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'query' => $searchQuery,
    'date' => $transaction->date_only,
    'month' => $transaction->month,
    'year' => $transaction->year,
]) }}
<div>
    {{ $transaction->description }}
</div>
<div style="margin-bottom: 6px;">
    @if ($transaction->loan)
        @php
            $loanRoute = route('loans.show', $transaction->loan);
        @endphp
        <a href="{{ $loanRoute }}">{!! $transaction->loan->type_label !!}</a>
    @endif
    @if ($transaction->partner)
        @php
            $partnerRoute = route('partners.show', [
                $transaction->partner_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        @endphp
        <a href="{{ $partnerRoute }}">{!! $transaction->partner->name_label !!}</a>
    @endif
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
