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
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('year', __('time.year'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        {{ Form::select('month', $months, $month, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.finance.dashboard', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.finance.dashboard', __('report.this_year'), ['year' => now()->format('Y'), 'month' => '00'], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group mt-0">
            @if ($month == '00')
                {{ link_to_route('reports.finance.dashboard', __('report.prev_year'), ['year' => $year - 1, 'month' => '00'], ['class' => 'btn btn-secondary mr-1']) }}
                {{ link_to_route('reports.finance.dashboard', __('report.next_year'), ['year' => $year + 1, 'month' => '00'], ['class' => 'btn btn-secondary mr-1']) }}
            @else
                @livewire('prev-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn btn-secondary mr-1'])
                @livewire('next-month-button', ['routeName' => 'reports.finance.dashboard', 'buttonClass' => 'btn btn-secondary'])
            @endif
        </div>
        {{ Form::close() }}
    </div>
</div>

@if ($month == '00')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('transaction.balance') }}</div>
                    <div class="table-responsive">
                        @livewire('dashboard.balance-by-months', [
                            'book' => $book,
                            'year' => $year,
                            'selectedMonth' => $month,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                        ])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending_category') }}</div>
                    @livewire('dashboard.top-category', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'spending',
                    ])
                </div>
            </div>
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income_category') }}</div>
                    @livewire('dashboard.top-category', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'income',
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending') }}</div>
                    @livewire('dashboard.top-transaction', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'spending',
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income') }}</div>
                    @livewire('dashboard.top-transaction', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'income',
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.daily_averages') }}</div>
                    @livewire('dashboard.daily-averages', [
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'book' => $book,
                   ])
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('transaction.balance') }}</div>
                    <div class="table-responsive">
                        @livewire('dashboard.balance-by-months', [
                            'book' => $book,
                            'year' => $year,
                            'selectedMonth' => $month,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                        ])
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending_category') }}</div>
                    @livewire('dashboard.top-category', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'spending',
                    ])
                </div>
            </div>
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income_category') }}</div>
                    @livewire('dashboard.top-category', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'income',
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending') }}</div>
                    @livewire('dashboard.top-transaction', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'spending',
                    ])
                </div>
            </div>
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income') }}</div>
                    @livewire('dashboard.top-transaction', [
                        'book' => $book,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'typeCode' => 'income',
                    ])
                </div>
            </div>
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-muted mb-2 text-center">{{ __('dashboard.daily_averages') }}</div>
                    @livewire('dashboard.daily-averages', [
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'book' => $book,
                   ])
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
