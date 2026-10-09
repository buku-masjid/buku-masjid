@extends('layouts.app')

@section('title', __('transaction.list'))

@section('content')
<div class="page-header">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title"><div class="d-inline">{{ __('transaction.list') }}&nbsp;</div>{{ get_months()[$month] }} {{ $year }}</h2>
            <div class="text-secondary mt-1">{{ __('app.total') }} : {{ $transactions->count() }} {{ __('transaction.transaction') }}</div>
        </div>
        <div class="col-sm-auto mt-3 mt-sm-2 ms-auto">
            {{ link_to_route('transaction_search.index', __('app.search'), [], ['class' => 'btn px-3']) }}
            @can('create', new App\Transaction)
                @can('manage-transactions', auth()->activeBook())
                    {{ link_to_route('transactions.create', __('transaction.add_income'), ['action' => 'add-income', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success px-2']) }}
                    {{ link_to_route('transactions.create', __('transaction.add_spending'), ['action' => 'add-spending', 'month' => $month, 'year' => $year], ['class' => 'btn btn-danger px-2']) }}
                @endcan
            @endcan
        </div>
    </div>
</div>

<div class="page-body">
<div class="row">
    <div class="col-md-12">
        @include('transactions.partials.stats')
        @include('transactions.partials.index_filters')
        <div class="card table-responsive">
            @desktop
            <div class="table-responsive-sm">
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('app.table_no') }}</th>
                            <th class="text-center">{{ __('app.date') }}</th>
                            <th>{{ __('transaction.description') }}</th>
                            <th class="text-end">{{ __('transaction.amount') }}</th>
                            <th class="text-center">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $transaction)
                        @php
                            $groups = $transactions->where('date_only', $transaction->date_only);
                            $firstGroup = $groups->first();
                            $groupCount = $groups->count();
                        @endphp
                        <tr>
                            <td class="text-center">{{ 1 + $key }}</td>
                            @if ($firstGroup->id == $transaction->id)
                                <td class="text-center text-middle" rowspan="{{ $groupCount }}">
                                    {{ $transaction->day_name }},
                                    {{ link_to_route('transactions.index', $transaction->date_only.'-'.$transaction->month_name, [
                                        'date' => $transaction->date_only,
                                        'month' => $month,
                                        'year' => $year,
                                        'category_id' => request('category_id'),
                                    ]) }}
                                </td>
                            @endif
                            <td>
                                <span class="float-end">
                                    @if ($transaction->files_count)
                                        <a href="{{ route('transactions.show', $transaction) }}" class="badge bg-gray text-dark" style="font-size: 90%;padding: 0.1em 0.1em">
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
                                    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple text-purple-fg' : 'bg-secondary text-secondary-fg'}}">
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
                                </span>
                                <div style="max-width: 600px" class="me-3">{!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}</div>
                            </td>
                            <td class="text-end">{{ $transaction->amount_string }}</td>
                            <td class="text-center">
                                @can('update', $transaction)
                                    @can('manage-transactions', auth()->activeBook())
                                        {!! link_to_route(
                                            'transactions.edit',
                                            __('app.edit'),
                                            [$transaction->id, 'reference_page' => 'transactions'] + request(['month', 'year', 'query', 'category_id', 'bank_account_id']),
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
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
                        @endforelse
                    </tbody>
                    @if (request('category_id') || request('book_id'))
                    <tfoot>
                        <tr><td colspan="5" class="text-end">&nbsp;</td></tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.income_total') }}</td>
                            <td class="text-end">{{ format_number($incomeTotal) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.spending_total') }}</td>
                            <td class="text-end">{{ format_number($spendingTotal) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.difference') }}</td>
                            <td class="text-end">{{ format_number($incomeTotal - $spendingTotal) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                    @else
                    <tfoot>
                        <tr><td colspan="5" class="text-end">&nbsp;</td></tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.start_balance') }}</td>
                            <td class="text-end">
                                {{ format_number($balance = auth()->activeBook()->getBalance(Carbon\Carbon::parse($startDate)->subDay()->format('Y-m-d'))) }}
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.income_total') }}</td>
                            <td class="text-end">{{ format_number($incomeTotal) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.spending_total') }}</td>
                            <td class="text-end">{{ format_number($spendingTotal) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="strong">
                            <td colspan="3" class="text-end">{{ __('transaction.end_balance') }}</td>
                            <td class="text-end">
                                {{ format_number($balance + $incomeTotal - $spendingTotal) }}
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @elsedesktop
            <div class="card-body">
                @foreach ($transactions->groupBy('date') as $groupedTransactions)
                    <h5 class="text-center mb-0">{{ $groupedTransactions->first()->day_name }}</h5>
                    @foreach ($groupedTransactions as $date => $transaction)
                        @include('transactions.partials.single_transaction_mobile', ['transaction' => $transaction, 'month' => $month, 'year' => $year])
                    @endforeach
                    <hr class="my-2">
                @endforeach
                @include('transactions.partials.transaction_summary_mobile', ['transactions' => $transactions])
            </div>
            @enddesktop
        </div>
    </div>
</div>
</div>
@endsection
