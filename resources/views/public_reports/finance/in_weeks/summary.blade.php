@extends('layouts.public_reports')

@section('subtitle', __('report.in_weeks'))

@section('content-report')
@if ($showBudgetSummary)
    @include('reports.finance._internal_periode_summary')
@endif

<div class="card table-responsive">
    @include('public_reports.finance._public_content_summary')
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
