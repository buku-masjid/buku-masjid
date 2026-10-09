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
    <div class="page-options d-flex mb-3">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('year', __('time.year'), ['class' => 'form-label mt-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $year, ['class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', $months, $month, ['class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info']) }}
                {{ link_to_route('reports.finance.dashboard', __('report.this_month'), [], ['class' => 'btn']) }}
                {{ link_to_route('reports.finance.dashboard', __('report.this_year'), ['year' => now()->format('Y'), 'month' => '00'], ['class' => 'btn']) }}
                @include('reports.finance._export_pdf_split_button', [
                'pdfRoute' => 'reports.finance.dashboard_pdf',
                'pdfParams' => request()->only(['year', 'month']),
            ])
            </div>
            <div class="col-auto">
                @if ($month == '00')
                    {{ link_to_route('reports.finance.dashboard', __('report.prev_year'), ['year' => $year - 1, 'month' => '00'], ['class' => 'btn']) }}
                    {{ link_to_route('reports.finance.dashboard', __('report.next_year'), ['year' => $year + 1, 'month' => '00'], ['class' => 'btn']) }}
                @else
                    @livewire('prev-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn'])
                    @livewire('next-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn'])
                @endif
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('reports.finance._internal_content_dashboard')

@endsection
