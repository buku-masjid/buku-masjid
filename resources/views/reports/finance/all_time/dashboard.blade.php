@extends('layouts.reports')

@section('subtitle', __('dashboard.dashboard'))

@section('content-report')
<div class="page-header mt-0 mb-4">
    <h1 class="page-title">
        <div class="d-none d-sm-inline">{{ __('dashboard.dashboard') }}</div>
        {{ get_date_range_text($startDate->format('Y-m-d'), $endDate->format('Y-m-d')) }}
    </h1>
    <div class="page-subtitle"></div>
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
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info me-1']) }}
                {{ link_to_route('reports.finance.dashboard', __('app.reset'), [], ['class' => 'btn me-1']) }}
                {{ link_to_route('reports.finance.dashboard_pdf', __('report.export_pdf'), request()->only(['start_date', 'end_date']), ['class' => 'btn me-1']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('reports.finance._internal_content_dashboard')

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
