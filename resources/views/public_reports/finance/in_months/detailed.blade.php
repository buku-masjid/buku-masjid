@extends('layouts.public_reports')

@section('subtitle', __('report.weekly'))

@section('content-report')
@php
    $lastWeekDate = null;
@endphp
@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    @php
        $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
    @endphp
    <div class="card-header">
        <h3 class="card-title">
            {{ __('time.week') }} {{ $weekNumber + 1 }}
            <span class="small">({{ $weekLabels[$weekNumber] }})</span>
        </h3>
    </div>
    @include('public_reports.finance._public_content_detailed')
    @php
        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
    @endphp
</div>
@endforeach
@endsection
