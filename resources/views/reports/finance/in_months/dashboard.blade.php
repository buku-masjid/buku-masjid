@extends('layouts.reports')

@section('subtitle', __('dashboard.dashboard'))

@section('content-report')
<div class="page-header mt-0 mb-4">
    <h1 class="page-title">
        <div class="d-none d-sm-inline">{{ __('dashboard.dashboard') }}</div>
        @if ($month != '00')
            {{ $months[$month] }} {{ $year }}
        @else
            {{ __('time.year') }} {{ $year }}
        @endif
    </h1>
    <div class="page-subtitle"></div>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('year', __('time.year'), ['class' => 'control-label me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $year, ['class' => 'form-control me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', $months, $month, ['class' => 'form-control me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info me-1']) }}
                {{ link_to_route('reports.finance.dashboard', __('report.this_month'), [], ['class' => 'btn me-1']) }}
                {{ link_to_route('reports.finance.dashboard', __('report.this_year'), ['year' => now()->format('Y'), 'month' => '00'], ['class' => 'btn me-1']) }}
                {{ link_to_route('reports.finance.dashboard_pdf', __('report.export_pdf'), request()->only(['year', 'month']), ['class' => 'btn me-1']) }}
            </div>
            <div class="col-auto">
                @if ($month == '00')
                    {{ link_to_route('reports.finance.dashboard', __('report.prev_year'), ['year' => $year - 1, 'month' => '00'], ['class' => 'btn me-1']) }}
                    {{ link_to_route('reports.finance.dashboard', __('report.next_year'), ['year' => $year + 1, 'month' => '00'], ['class' => 'btn me-1']) }}
                @else
                    @livewire('prev-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn me-1'])
                    @livewire('next-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn'])
                @endif
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('reports.finance._internal_content_dashboard')

@endsection
