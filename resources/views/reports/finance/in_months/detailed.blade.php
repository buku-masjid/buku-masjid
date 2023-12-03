@extends('layouts.reports')

@section('subtitle', __('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

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
            {{ auth()->activeBook()->report_titles['finance_detailed'] }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            {{ __('report.weekly') }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
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
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $startDate->format('m'), ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.finance.detailed', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.finance.detailed_pdf', __('report.export_pdf'), ['year' => $startDate->format('Y'), 'month' => $startDate->format('m')], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-month-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    @include('reports.finance._internal_content_detailed')
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
