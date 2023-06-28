@extends('layouts.app')

@section('title', __('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Pemasukan dan Pengeluaran Kas {{ $currentMonthEndDate->isoFormat('MMMM Y') }}</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.in_out', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            {{ link_to_route('reports.in_out', __('report.prev_month'), ['month' => $prevMonthDate->format('m'), 'year' => $prevMonthDate->format('Y')], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.in_out', __('report.next_month'), ['month' => $nextMonthDate->format('m'), 'year' => $nextMonthDate->format('Y')], ['class' => 'btn btn-secondary']) }}
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="page-header mt-0 mb-2">
            <h2 class="page-title">{{ __('transaction.income') }}</h2>
        </div>

        @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
        <h4 class="mt-0">{{ $incomeCategory->name }}</h4>
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr >
                        <th class="text-center col-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-2">{{ __('time.date') }}</th>
                        <th class=" col-4">{{ __('app.description') }}</th>
                        <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                    </tr>
                </thead>
                @if ($groupedTransactions->has(1))
                <tbody>
                    @php
                        $key = 0;
                    @endphp
                    @foreach ($groupedTransactions[1]->where('category_id', $incomeCategory->id) as $transaction)
                    <tr>
                        <td class="text-center col-1">{{ ++$key }}</td>
                        <td class="text-center col-2">{{ $transaction->date }}</td>
                        <td class="col-4">{{ $transaction->description }}</td>
                        <td class="text-right col-3">{{ $transaction->amount_string }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('app.total') }} {{ $incomeCategory->name }}</th>
                        <th class="text-right">
                            {{ number_format($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount'), 2) }}
                        </th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach
    </div>
    <div class="col-lg-6">
        <div class="page-header mt-0 mb-2">
            <h2 class="page-title">{{ __('transaction.spending') }}</h2>
        </div>

        @foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
        <h4 class="mt-0">{{ $spendingCategory->name }}</h4>
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr >
                        <th class="text-center col-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-2">{{ __('time.date') }}</th>
                        <th class=" col-4">{{ __('app.description') }}</th>
                        <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                    </tr>
                </thead>
                @if ($groupedTransactions->has(0))
                <tbody>
                    @php
                        $key = 0;
                    @endphp
                    @foreach ($groupedTransactions[0]->where('category_id', $spendingCategory->id) as $transaction)
                    <tr>
                        <td class="text-center col-1">{{ ++$key }}</td>
                        <td class="text-center col-2">{{ $transaction->date }}</td>
                        <td class="col-4">{{ $transaction->description }}</td>
                        <td class="text-right col-3">{{ $transaction->amount_string }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('app.total') }} {{ $spendingCategory->name }}</th>
                        <th class="text-right">
                            - {{ number_format($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount'), 2) }}
                        </th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach
    </div>
</div>


@endsection
