@extends('layouts.public_reports')

@section('subtitle', __('report.finance_detailed'))

@section('content-report')

<div class="page-header mt-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.finance.detailed', __('report.this_week'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group mt-4 mt-sm-0">
            @livewire('prev-week-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-week-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary'])
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
        <h3 class="card-title">{{ __('time.week') }} {{ ++$weekNumber }}</h3>
    </div>
    @include('public_reports.finance._public_content_detailed')
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
