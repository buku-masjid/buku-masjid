@extends('layouts.public_reports')

@section('subtitle', __('report.finance_categorized'))

@section('content-report')
<div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.income') }}</h2>
</div>

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
<h4 class="mt-2">{{ $incomeCategory->name }}</h4>
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
    <h4 class="mt-2">{{ __('transaction.no_category') }}</h4>
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
<h4 class="mt-2">{{ $spendingCategory->name }}</h4>
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
    <h4 class="mt-2">{{ __('transaction.no_category') }}</h4>
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
