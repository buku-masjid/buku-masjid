@extends('layouts.reports')

@section('subtitle', __('report.finance_detailed'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'detailed',
        'existingReportTitle' => __('report.finance_detailed'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_detailed']))
            {{ auth()->activeBook()->report_titles['finance_detailed'] }}
        @else
            {{ __('report.finance_detailed') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.detailed',
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
                {{ link_to_route('reports.finance.detailed', __('app.reset'), [], ['class' => 'btn me-1']) }}
                {{ link_to_route('reports.finance.detailed_pdf', __('report.export_pdf'), ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'bank_account_id' => request('bank_account_id')], ['class' => 'btn me-1']) }}
            </div>
            <div class="col-auto">
                @livewire('prev-week-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn me-1'])
                @livewire('next-week-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@php
    $lastWeekDate = null;
@endphp
@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    @php
        $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
    @endphp
    <div class="card-header">
        <h3 class="card-title">
            {{ __('time.week') }} {{ $weekNumber + 1 }}
            <span class="small">({{ $weekLabels[$weekNumber] }})</span>
        </h3>
    </div>
    @include('reports.finance._internal_content_detailed')
    @php
        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
    @endphp
</div>
@endforeach
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
