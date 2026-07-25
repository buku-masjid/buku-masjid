@extends('layouts.reports')

@section('subtitle', __('report.monthly'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'summary',
        'existingReportTitle' => __('report.monthly'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_summary']))
            {{ auth()->activeBook()->report_titles['finance_summary'] }}
        @else
            {{ __('report.monthly') }}
        @endif
        @if (request('month') != '00')
            - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            - {{ $currentMonthEndDate->isoFormat('Y') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.summary',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex mb-3">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::label('month', __('time.month'), ['class' => 'control-label mt-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', ['00' => '-- '.__('app.all').' --'] + get_months(), request('month', $startDate->format('m')), ['class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => __('transaction.origin_destination'), 'class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info']) }}
                {{ link_to_route('reports.finance.summary', __('report.this_month'), [], ['class' => 'btn']) }}
                {{ link_to_route('reports.finance.summary_pdf', __('report.export_pdf'), ['year' => $startDate->format('Y'), 'month' => request('month', $startDate->format('m')), 'bank_account_id' => request('bank_account_id')], ['class' => 'btn']) }}
            </div>
            @if (request('month') != '00')
                <div class="col-auto">
                    @livewire('prev-month-button', ['routeName' => 'reports.finance.summary', 'buttonClass' => 'btn'])
                    @livewire('next-month-button', ['routeName' => 'reports.finance.summary', 'buttonClass' => 'btn'])
                </div>
            @endif
        </div>
        {{ Form::close() }}
    </div>
</div>

@if ($showBudgetSummary)
    @include('reports.finance._internal_periode_summary')
@endif

<div class="card table-responsive">
    @include('reports.finance._internal_content_summary')
</div>
@endsection

