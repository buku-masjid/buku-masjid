@extends('layouts.reports')

@section('subtitle', __('report.in_weeks'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'summary',
        'existingReportTitle' => __('report.in_weeks'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_summary']))
            {{ auth()->activeBook()->report_titles['finance_summary'] }}
        @else
            {{ __('report.in_weeks') }}
        @endif
        {{ __('time.period') }} {{ get_date_range_text($startDate->format('Y-m-d'), $endDate->format('Y-m-d')) }}

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.summary',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control me-1', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control me-1', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => __('transaction.origin_destination'), 'class' => 'form-control me-1', 'style' => 'width:115px']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info me-1']) }}
                {{ link_to_route('reports.finance.summary', __('app.reset'), [], ['class' => 'btn me-1']) }}
                {{ link_to_route('reports.finance.summary_pdf', __('report.export_pdf'), ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'bank_account_id' => request('bank_account_id')], ['class' => 'btn me-1']) }}
            </div>
            <div class="col-auto">
                @livewire('prev-week-button', ['routeName' => 'reports.finance.summary', 'buttonClass' => 'btn me-1'])
                @livewire('next-week-button', ['routeName' => 'reports.finance.summary', 'buttonClass' => 'btn'])
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

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

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
