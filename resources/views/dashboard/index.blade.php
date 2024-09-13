@extends('layouts.reports')

@section('subtitle', __('dashboard.dashboard'))

@section('content-report')
<div class="page-header mt-0 mb-4">
    <h1 class="page-title"><div class="d-none d-sm-inline">{{ __('dashboard.dashboard') }}</div> {{ __('time.year') }} {{ $year }}</h1>
    <div class="page-subtitle"></div>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('year', __('time.year'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('dashboard.index', __('report.this_year'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('dashboard.index', __('report.prev_year'), ['year' => $year - 1], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('dashboard.index', __('report.next_year'), ['year' => $year + 1], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('transaction.balance') }}</div>
                @livewire('dashboard.balance-by-months', ['year' => $year, 'book' => $book])
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('dashboard.top_spending_category') }}</div>
                @livewire('dashboard.top-category', ['year' => $year, 'book' => $book, 'typeCode' => 'spending'])
            </div>
        </div>
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('dashboard.top_income_category') }}</div>
                @livewire('dashboard.top-category', ['year' => $year, 'book' => $book, 'typeCode' => 'income'])
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('dashboard.top_spending') }}</div>
                @livewire('dashboard.top-transaction', ['year' => $year, 'book' => $book, 'typeCode' => 'spending'])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('dashboard.top_income') }}</div>
                @livewire('dashboard.top-transaction', ['year' => $year, 'book' => $book, 'typeCode' => 'income'])
            </div>
        </div>
    </div>
</div>
@endsection
