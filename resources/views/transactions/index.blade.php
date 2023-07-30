@extends('layouts.app')

@section('title', __('transaction.list'))

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ __('transaction.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $transactions->count() }} {{ __('transaction.transaction') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('transaction_search.index', __('app.search'), [], ['class' => 'btn btn-secondary mr-2']) }}
        @can('create', new App\Transaction)
            {{ link_to_route('transactions.index', __('transaction.add_income'), ['action' => 'add-income', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success mr-2']) }}
            {{ link_to_route('transactions.index', __('transaction.add_spending'), ['action' => 'add-spending', 'month' => $month, 'year' => $year], ['class' => 'btn btn-danger']) }}
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @include('transactions.partials.stats')
        <div class="card table-responsive">
            <div class="card-header">
                @include('transactions.partials.index_filters')
            </div>
            @desktop
            <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-center">{{ __('app.date') }}</th>
                        <th>{{ __('transaction.description') }}</th>
                        <th class="text-right">{{ __('transaction.amount') }}</th>
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
                            <span class="float-right">
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
                            {{ $transaction->description }}
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                {!! link_to_route(
                                    'transactions.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $transaction->id] + request(['month', 'year', 'query', 'category_id']),
                                    ['id' => 'edit-transaction-'.$transaction->id]
                                ) !!}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
                    @endforelse
                </tbody>
                @if (request('category_id') || request('book_id'))
                <tfoot>
                    <tr><td colspan="5" class="text-right">&nbsp;</td></tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.income_total') }}</td>
                        <td class="text-right">{{ format_number($incomeTotal) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.spending_total') }}</td>
                        <td class="text-right">{{ format_number($spendingTotal) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.difference') }}</td>
                        <td class="text-right">{{ number_format($incomeTotal - $spendingTotal, 2) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
                @else
                <tfoot>
                    <tr><td colspan="5" class="text-right">&nbsp;</td></tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.start_balance') }}</td>
                        <td class="text-right">
                            @php
                                $balance = 0;
                            @endphp
                            @if ($transactions->first())
                                {{ format_number($balance = auth()->activeBook()->getBalance(Carbon\Carbon::parse($transactions->first()->date)->subDay()->format('Y-m-d'))) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.income_total') }}</td>
                        <td class="text-right">{{ format_number($incomeTotal) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.spending_total') }}</td>
                        <td class="text-right">{{ format_number($spendingTotal) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('transaction.end_balance') }}</td>
                        <td class="text-right">
                            @if ($transactions->first())
                                {{ format_number($balance + $incomeTotal - $spendingTotal) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
                @endif
            </table>
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
            @enddesktop
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('transactions.forms')
        @endif
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#transactionModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
})();
</script>
@endpush
