@extends('layouts.reports')

@section('subtitle', __('report.finance_categorized'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    <div id="reportModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.change_report_title') }}</h5>
                    {{ link_to_route('reports.finance.categorized', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['books.report_titles.update', request('book_id')], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @php
                        $existingReportTitle = __('report.finance_categorized');
                        if (isset(auth()->activeBook()->report_titles['finance_categorized'])) {
                            $existingReportTitle = auth()->activeBook()->report_titles['finance_categorized'];
                        }
                        $reportTitle = old('report_titles[finance_categorized]', $existingReportTitle);
                    @endphp
                    {{ Form::text('report_titles[finance_categorized]', $reportTitle, [
                        'required' => true,
                        'class' => 'form-control',
                    ]) }}
                    {{ Form::hidden('book_id', request('book_id')) }}
                    {{ Form::hidden('nonce', request('nonce')) }}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.change_report_title'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('reports.finance.categorized', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    {!! Form::submit(__('book.reset_report_title'), ['class' => 'btn btn-secondary', 'name' => 'reset_report_title[finance_categorized]']) !!}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif

<div class="page-header my-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_categorized']))
            {{ auth()->activeBook()->report_titles['finance_categorized'] }}
        @else
            {{ __('report.finance_categorized') }}
        @endif

        @if (!request('action'))
            @can('update', auth()->activeBook())
                {{ link_to_route(
                    'reports.finance.categorized',
                    __('book.change_report_title'),
                    request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                    ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
                ) }}
            @endcan
        @endif
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.finance.categorized', __('app.reset'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.finance.categorized_pdf', __('report.export_pdf'), ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header mt-0 mb-2">
            <h2 class="page-title">{{ __('transaction.income') }}</h2>
        </div>

        @if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
            <h4 class="mt-0 text-danger">~{{ __('transaction.no_category') }}~</h4>
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
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[1]->where('category_id', null) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="strong">
                            <td colspan="3" class="text-right">{{ __('app.total') }} {{ __('transaction.no_category') }}</td>
                            <td class="text-right">
                                {{ format_number($groupedTransactions[1]->where('category_id', null)->sum('amount')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

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
                        <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $incomeCategory->name }}</td>
                        <td class="text-right">
                            {{ format_number($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount')) }}
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

        @if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
            <h4 class="mt-0 text-danger">~{{ __('transaction.no_category') }}~</h4>
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
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[0]->where('category_id', null) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="strong">
                            <td colspan="3" class="text-right">{{ __('app.total') }} {{ __('transaction.no_category') }}</td>
                            <td class="text-right">
                                {{ format_number($groupedTransactions[0]->where('category_id', null)->sum('amount')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

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
                        <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $spendingCategory->name }}</td>
                        <td class="text-right">
                            {{ format_number($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
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

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#reportModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        scrollMonth: false,
    });
})();
</script>
@endpush
