@extends('layouts.reports')

@section('subtitle', __('report.weekly'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'detailed',
        'existingReportTitle' => __('report.weekly'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_detailed']))
            {{ auth()->activeBook()->report_titles['finance_detailed'] }}
        @else
            {{ __('report.weekly') }}
        @endif
        @if (request('month') != '00')
            - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            - {{ $currentMonthEndDate->isoFormat('Y') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.detailed',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('month', __('time.month'), ['class' => 'control-label me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', ['00' => '-- '.__('app.all').' --'] + get_months(), request('month', $startDate->format('m')), ['class' => 'form-control me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => __('transaction.origin_destination'), 'class' => 'form-control me-1']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info me-1']) }}
                {{ link_to_route('reports.finance.detailed', __('report.this_month'), [], ['class' => 'btn btn-sm me-1']) }}
                {{ link_to_route('reports.finance.detailed_pdf', __('report.export_pdf'), ['year' => $startDate->format('Y'), 'month' => request('month', $startDate->format('m')), 'bank_account_id' => request('bank_account_id')], ['class' => 'btn btn-sm me-1']) }}
            </div>
            @if (request('month') != '00')
                <div class="col-auto">
                    @livewire('prev-month-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-sm me-1'])
                    @livewire('next-month-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-sm'])
                </div>
            @endif
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
        <h3 class="card-title">
            {{ __('time.week') }} {{ $weekNumber + 1 }}
            <span class="small">({{ $weekLabels[$weekNumber] }})</span>
        </h3>
        <div class="card-options"></div>
    </div>
    @include('reports.finance._internal_content_detailed')
    @php
        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
    @endphp
</div>
@endforeach
@endsection

@push('scripts')
<script>
(function () {
    $('#reportModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
