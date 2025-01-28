@extends('layouts.public_reports')

@section('subtitle', __('report.weekly'))

@section('content-report')

<div class="page-header mt-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', ['00' => '-- '.__('app.all').' --'] + get_months(), request('month', $startDate->format('m')), ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.finance.detailed', __('report.this_month'), Request::except(['year', 'month']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        @if (request('month') != '00')
            <div class="form-group mt-4 mt-sm-0">
                @livewire('prev-month-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary mr-1'])
                @livewire('next-month-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary'])
            </div>
        @endif
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
