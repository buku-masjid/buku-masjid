@extends('layouts.public_reports')

@section('subtitle', __('report.finance_categorized'))

@section('content-report')

<div class="page-header my-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.finance.categorized', __('report.this_week'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group mt-4 mt-sm-0">
            @livewire('prev-week-button', ['routeName' => 'public_reports.finance.categorized', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-week-button', ['routeName' => 'public_reports.finance.categorized', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.income') }}</h2>
</div>

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
<h4 class="mt-0">{{ $incomeCategory->name }}</h4>
<div class="card table-responsive">
    @include('public_reports.finance._public_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(1),
        'isForInternal' => $incomeCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL,
        'transactions' => $groupedTransactions[1]->where('category_id', $incomeCategory->id),
        'categoryName' => $incomeCategory->name,
    ])
</div>
@endforeach

@if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
    <h4 class="mt-0">{{ __('transaction.no_category') }}</h4>
    <div class="card table-responsive">
        @include('public_reports.finance._public_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(1),
            'isForInternal' => false,
            'transactions' => $groupedTransactions[1]->where('category_id', null),
            'categoryName' => __('transaction.no_category'),
        ])
    </div>
@endif

<div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.spending') }}</h2>
</div>

@foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
<h4 class="mt-0">{{ $spendingCategory->name }}</h4>
<div class="card table-responsive">
    @include('public_reports.finance._public_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(0),
        'isForInternal' => $spendingCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL,
        'transactions' => $groupedTransactions[0]->where('category_id', $spendingCategory->id),
        'categoryName' => $spendingCategory->name,
    ])
</div>
@endforeach

@if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
    <h4 class="mt-0">{{ __('transaction.no_category') }}</h4>
    <div class="card table-responsive">
        @include('public_reports.finance._public_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(0),
            'isForInternal' => false,
            'transactions' => $groupedTransactions[0]->where('category_id', null),
            'categoryName' => __('transaction.no_category'),
        ])
    </div>
@endif
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        scrollMonth: false,
    });
})();
</script>
@endpush
