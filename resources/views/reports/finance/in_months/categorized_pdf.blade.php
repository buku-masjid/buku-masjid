@extends('layouts.print')

@section('title', __('report.categorized_transactions'))

@section('content')
{{-- ref: https://github.com/niklasravnsborg/laravel-pdf#headers-and-footers --}}
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
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
    </h2>
</htmlpageheader>

<h2>{{ __('transaction.income') }}</h2>

@if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
    <div style="page-break-inside: avoid">
        <h4 class="text-danger">~{{ __('transaction.no_category') }}~</h4>
        <div>
            @include('reports.finance._internal_content_categorized', [
                'hasGroupedTransactions' => $groupedTransactions->has(1),
                'transactions' => $groupedTransactions[1]->where('category_id', null),
                'categoryName' => __('transaction.no_category'),
            ])
        </div>
    </div>
@endif

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
{{-- ref: https://mpdf.github.io/paging/page-breaks.html#tables --}}
<div style="page-break-inside: avoid">
    <h4>{{ $incomeCategory->name }}</h4>
    <div>
        @include('reports.finance._internal_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(1),
            'transactions' => $groupedTransactions[1]->where('category_id', $incomeCategory->id),
            'categoryName' => $incomeCategory->name,
        ])
    </div>
</div>
@endforeach

<pagebreak />

<h2>{{ __('transaction.spending') }}</h2>

@if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
    <div style="page-break-inside: avoid">
        <h4 class="text-danger">~{{ __('transaction.no_category') }}~</h4>
        <div>
            @include('reports.finance._internal_content_categorized', [
                'hasGroupedTransactions' => $groupedTransactions->has(0),
                'transactions' => $groupedTransactions[0]->where('category_id', null),
                'categoryName' => __('transaction.no_category'),
            ])
        </div>
    </div>
@endif

@foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
<div style="page-break-inside: avoid">
    <h4>{{ $spendingCategory->name }}</h4>
    <div class="card table-responsive">
        @include('reports.finance._internal_content_categorized', [
            'hasGroupedTransactions' => $groupedTransactions->has(0),
            'transactions' => $groupedTransactions[0]->where('category_id', $spendingCategory->id),
            'categoryName' => $spendingCategory->name,
        ])
    </div>
</div>
@endforeach

@include('reports.finance._pdf_signature_content')

<htmlpagefooter name="wpFooter">
    @if (Setting::for(auth()->activeBook())->get('has_pdf_page_number') != '0')
        <div class="text-right">{{ __('report.page') }} {PAGENO}/{nb}</div>
    @endif
</htmlpagefooter>
@endsection

@section('style')
<style>
    @page {
        size: auto;
        margin-top: @if($showLetterhead) 170px; @else 100px; @endif
        margin-bottom: 50px;
        margin-left: 50px;
        margin-right: 50px;
        margin-header: 40px;
        margin-footer: 40px;
        header: html_wpHeader;
        footer: html_wpFooter;
    }
</style>
@endsection
