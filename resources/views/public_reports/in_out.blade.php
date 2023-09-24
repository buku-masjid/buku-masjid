@extends('layouts.public_reports')

@section('subtitle', __('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

<div class="page-header mt-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.in_out', __('report.this_month'),  Request::except(['year', 'month']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group mb-0">
            @livewire('prev-month-button', ['routeName' => 'public_reports.in_out', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'public_reports.in_out', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
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
                @if ($incomeCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                @else
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[1]->where('category_id', $incomeCategory->id) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ number_format($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $incomeCategory->name }}</td>
                        <td class="text-right">
                            {{ number_format($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount')) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach

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
                @if ($spendingCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                    <tbody>
                        <tr>
                            <td class="text-center col-1">&nbsp;</td>
                            <td class="text-center col-2">&nbsp;</td>
                            <td class="col-4">{{ $spendingCategory->name }}</td>
                            <td class="text-right col-3">
                                {{ number_format($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                            </td>
                        </tr>
                    </tbody>
                @else
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[0]->where('category_id', $spendingCategory->id) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ number_format($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $spendingCategory->name }}</td>
                        <td class="text-right">
                            {{ number_format($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach
    </div>
</div>


@endsection
