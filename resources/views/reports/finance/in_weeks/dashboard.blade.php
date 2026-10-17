@extends('layouts.reports')

@section('subtitle', __('dashboard.dashboard'))

@section('content-report')
<div class="page-header mt-0 mb-4">
    <h1 class="page-title">
        <div class="d-none d-sm-inline">{{ __('dashboard.dashboard') }}</div>
        {{ get_date_range_text($startDate->format('Y-m-d'), $endDate->format('Y-m-d')) }}
    </h1>
    <div class="page-subtitle"></div>
    <div class="page-options d-flex mb-3">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'form-label mt-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control', 'style' => 'width:100px']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info']) }}
                {{ link_to_route('reports.finance.dashboard', __('app.reset'), [], ['class' => 'btn']) }}
                @include('reports.finance._export_pdf_split_button', [
                'pdfRoute' => 'reports.finance.dashboard_pdf',
                'pdfParams' => request()->only(['start_date', 'end_date']),
            ])
            </div>
            <div class="col-auto">
                @livewire('prev-week-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn'])
                @livewire('next-week-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('reports.finance._internal_content_dashboard')

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
