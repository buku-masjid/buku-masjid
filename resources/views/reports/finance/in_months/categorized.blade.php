@extends('layouts.reports')

@section('subtitle', __('report.categorized_transactions'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    @include('reports.finance._edit_report_title_form', [
        'reportType' => 'categorized',
        'existingReportTitle' => __('report.categorized_transactions'),
    ])
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_categorized']))
            {{ auth()->activeBook()->report_titles['finance_categorized'] }}
        @else
            {{ __('report.categorized_transactions') }}
        @endif
        @if (request('month') != '00')
            - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            - {{ $currentMonthEndDate->isoFormat('Y') }}
        @endif

        @if (!request('action'))
            @can('update', auth()->activeBook())
                {{ link_to_route(
                    'reports.finance.categorized',
                    __('book.change_report_title'),
                    request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                    ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
                ) }}
            @endcan
        @endif
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('time.month'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', ['00' => '-- '.__('app.all').' --'] + get_months(), request('month', $startDate->format('m')), ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control mr-1']) }}
        {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => __('transaction.origin_destination'), 'class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.finance.categorized', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.finance.categorized_pdf', __('report.export_pdf'), ['year' => $startDate->format('Y'), 'month' => request('month', $startDate->format('m')), 'bank_account_id' => request('bank_account_id')], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        @if (request('month') != '00')
            <div class="form-group">
                @livewire('prev-month-button', ['routeName' => 'reports.finance.categorized', 'buttonClass' => 'btn btn-secondary mr-1'])
                @livewire('next-month-button', ['routeName' => 'reports.finance.categorized', 'buttonClass' => 'btn btn-secondary'])
            </div>
        @endif
        {{ Form::close() }}
    </div>
</div>

<div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.income') }}</h2>
</div>

@if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
    <h4 class="mt-0 text-danger">~{{ __('transaction.no_category') }}~</h4>
    <div class="card table-responsive">
        @include('reports.finance._internal_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(1),
            'transactions' => $groupedTransactions[1]->where('category_id', null),
            'categoryName' => __('transaction.no_category'),
        ])
    </div>
@endif

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
<h4 class="mt-0">{{ $incomeCategory->name }}</h4>
<div class="card table-responsive">
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(1),
        'transactions' => $groupedTransactions[1]->where('category_id', $incomeCategory->id),
        'categoryName' => $incomeCategory->name,
    ])
</div>
@endforeach

<div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.spending') }}</h2>
</div>

@if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
    <h4 class="mt-0 text-danger">~{{ __('transaction.no_category') }}~</h4>
    <div class="card table-responsive">
        @include('reports.finance._internal_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(0),
            'transactions' => $groupedTransactions[0]->where('category_id', null),
            'categoryName' => __('transaction.no_category'),
        ])
    </div>
@endif

@foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
<h4 class="mt-0">{{ $spendingCategory->name }}</h4>
<div class="card table-responsive">
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(0),
        'transactions' => $groupedTransactions[0]->where('category_id', $spendingCategory->id),
        'categoryName' => $spendingCategory->name,
    ])
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
