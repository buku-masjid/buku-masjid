@extends('layouts.reports')

@section('subtitle', __('dashboard.dashboard'))

@section('content-report')
<div class="page-header">
    <h1 class="page-title"><div class="d-none d-sm-inline">{{ __('dashboard.dashboard') }}</div> {{ get_months()[$month] }} {{ $year }}</h1>
    <div class="page-subtitle"></div>
    <div class="page-options d-flex"></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-4 text-center">{{ __('transaction.balance') }}</div>
                @livewire('dashboard.balance-by-months')
            </div>
        </div>
    </div>
</div>
@endsection
