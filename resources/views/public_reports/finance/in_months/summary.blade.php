@extends('layouts.public_reports')

@section('subtitle', __('report.monthly'))

@section('content-report')

@if ($showBudgetSummary)
    @include('reports.finance._internal_periode_summary')
@endif

<div class="card">
    <div class="table-responsive">
        @include('public_reports.finance._public_content_summary')
    </div>
</div>
@endsection
