@extends('layouts.reports')

@section('subtitle', __('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    <div id="reportModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.change_report_title') }}</h5>
                    {{ link_to_route('reports.in_out', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['books.report_titles.update', request('book_id')], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @php
                        $existingReportTitle = __('report.categorized_transactions');
                        if (isset(auth()->activeBook()->report_titles['in_out'])) {
                            $existingReportTitle = auth()->activeBook()->report_titles['in_out'];
                        }
                        $reportTitle = old('report_titles[in_out]', $existingReportTitle);
                    @endphp
                    {{ Form::text('report_titles[in_out]', $reportTitle, [
                        'required' => true,
                        'class' => 'form-control',
                    ]) }}
                    {{ Form::hidden('book_id', request('book_id')) }}
                    {{ Form::hidden('nonce', request('nonce')) }}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.change_report_title'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('reports.in_out', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    {!! Form::submit(__('book.reset_report_title'), ['class' => 'btn btn-secondary', 'name' => 'reset_report_title[in_out]']) !!}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['in_out']))
            {{ auth()->activeBook()->report_titles['in_out'] }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            {{ __('report.categorized_transactions') }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @endif

        @if (!request('action'))
            @can('update', auth()->activeBook())
                {{ link_to_route(
                    'reports.in_out',
                    __('book.change_report_title'),
                    request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                    ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
                ) }}
            @endcan
        @endif
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.in_out', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.in_out_pdf', __('report.export_pdf'), ['year' => $year, 'month' => $month], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-month-button', ['routeName' => 'reports.in_out', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'reports.in_out', 'buttonClass' => 'btn btn-secondary'])
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

@push('scripts')
<script>
(function () {
    $('#reportModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
