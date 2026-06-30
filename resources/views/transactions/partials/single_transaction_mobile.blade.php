<span class="float-end">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'date' => $transaction->date_only,
    'month' => $month,
    'year' => $year,
    'category_id' => request('category_id'),
]) }}
{!! $transaction->date_alert !!}
<div>
    {!! nl2br(htmlentities($transaction->description)) !!}
    <span class="float-end">
        @can('update', $transaction)
            @can('manage-transactions', auth()->activeBook())
                {!! link_to_route(
                    'transactions.edit',
                    __('app.edit'),
                    [$transaction->id, 'reference_page' => 'transactions'] + request(['query', 'category_id']),
                    ['id' => 'edit-transaction-'.$transaction->id]
                ) !!} |
            @endcan
        @endcan
        {{ link_to_route('transactions.show', __('app.detail'), $transaction) }}
        @can('create', new App\Transaction)
            | {{ link_to_route(
                'transactions.create',
                __('app.duplicate'),
                [
                    'action' => $transaction->in_out ? 'add-income' : 'add-spending',
                    'original_transaction_id' => $transaction->id,
                    'reference_page' => 'transactions',
                ] + request(['month', 'year', 'query', 'category_id', 'bank_account_id']),
                ['id' => 'duplicate-transaction-'.$transaction->id]
            ) }}
        @endcan
    </span>
</div>
<div style="margin-bottom: 6px;">
    @if ($transaction->files_count)
        <a href="{{ route('transactions.show', $transaction) }}" class="badge bg-gray text-dark px-1" style="font-size: 90%;">
            {{ $transaction->files_count }} <i class="ti ti-photo fs-4"></i>
        </a>
    @endif
    @if ($transaction->partner)
        @php
            $partnerRoute = route('partners.show', [
                $transaction->partner_id,
                'start_date' => $startDate,
                'end_date' => $year.'-'.$month.'-'.date('t'),
            ]);
        @endphp
        <a class="badge bg-info text-info-fg" href="{{ $partnerRoute }}">{{ $transaction->partner->name }}</a>
    @endif
    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple text-purple-fg' : 'bg-gray text-gray-fg'}}">
        {{ $transaction->bankAccount->name }}
    </span>
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
