@extends('layouts.reports')

@section('subtitle', __('report.all_time'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'summary',
        'existingReportTitle' => __('report.all_time'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_summary']))
            {{ auth()->activeBook()->report_titles['finance_summary'] }}
        @else
            {{ __('report.all_time') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.summary',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex mb-3">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mt-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => __('transaction.origin_destination'), 'class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info']) }}
                {{ link_to_route('reports.finance.summary', __('app.reset'), [], ['class' => 'btn']) }}
                @include('reports.finance._export_pdf_split_button', [
                'pdfRoute' => 'reports.finance.summary_pdf',
                'pdfParams' => ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'bank_account_id' => request('bank_account_id')],
            ])
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@if ($showBudgetSummary)
    @include('reports.finance._internal_periode_summary')
@endif

<div class="card table-responsive">
    @include('reports.finance._internal_content_summary')
</div>
@endsection

@push('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endpush

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
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
