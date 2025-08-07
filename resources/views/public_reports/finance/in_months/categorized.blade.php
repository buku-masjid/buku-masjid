@extends('layouts.public_reports')

@section('subtitle', __('report.categorized_transactions'))

@section('content-report')
<!-- <div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.income') }}</h2>
</div>  -->

<div class="accordion accordion-flush">
    <div class="d-none d-sm-block sticky-top border-bottom border-1" style="background-color: #f8f8f8;">
        <div class="row py-3 fs-2" style="padding-left: 1.25rem; padding-right: 48px">
            <div class="col-auto d-none"></div>
            <div class="col bm-fade fs-3 fw-bold rounded">{{ __('transaction.transaction') }}</div>
            <div class="col-2 p-0 bm-fade fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.income') }}</div>
            <div class="col-2 p-0 bm-fade fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.spending') }}</div>
        </div>
    </div>
    <div class="accordion-item card mb-2">
        <div class="accordion-header">
            <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#income" aria-expanded="false">
               {{ __('transaction.income') }}
            </button>
        </div>
         @php
            $key = 0;
        @endphp
        <div id="income" class="px-3 pb-3 accordion-collapse ">
             @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
                <div class="accordion-body transaction-list mb-0">
                    <h4 class="my-2">{{ $incomeCategory->name }}</h4>
                    <div class=" mb-3">
                        @include('public_reports.finance._public_content_categorized', [
                            'hasGroupedTransactions' => $groupedTransactions->has(1),
                            'isForInternal' => $incomeCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL,
                            'transactions' => $groupedTransactions[1]->where('category_id', $incomeCategory->id),
                            'categoryName' => $incomeCategory->name,
                            'typeTransactions' => 1,
                        ])
                    </div>
                </div>
            @endforeach

            @if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
                <div class="accordion-body transaction-list mb-0">
                    <h4 class="my-2 text-danger">~{{ __('transaction.no_category') }}~</h4>
                    <div class=" mb-3">
                        @include('public_reports.finance._public_content_categorized', [
                            'hasGroupedTransactions' => $groupedTransactions->has(1),
                            'isForInternal' => false,
                            'transactions' => $groupedTransactions[1]->where('category_id', null),
                            'categoryName' => __('transaction.no_category'),
                            'typeTransactions' => 1,
                        ])
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="accordion-item card mb-2">
        <div class="accordion-header">
            <button class="accordion-button collapsed fs-2 fw-bold bm-txt-out" type="button" data-bs-toggle="collapse" data-bs-target="#outcome" aria-expanded="false">
               {{ __('transaction.spending') }}
            </button>
        </div>
         @php
            $key = 0;
        @endphp
        <div id="outcome" class="px-3 pb-3 accordion-collapse ">
            @foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
                <div class="accordion-body transaction-list-out mb-0">
                    <h4 class="my-2">{{ $spendingCategory->name }}</h4>
                    <div class=" mb-3">
                        @include('public_reports.finance._public_content_categorized', [
                            'hasGroupedTransactions' => $groupedTransactions->has(0),
                            'isForInternal' => $spendingCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL,
                            'transactions' => $groupedTransactions[0]->where('category_id', $spendingCategory->id),
                            'categoryName' => $spendingCategory->name,
                            'typeTransactions' => 0,
                        ])
                    </div>
                </div>
            @endforeach

            @if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
                <div class="accordion-body transaction-list-out mb-0">
                    <h4 class="my-2 text-danger">~{{ __('transaction.no_category') }}~</h4>
                    <div class=" mb-3">
                        @include('public_reports.finance._public_content_categorized', [
                            'hasGroupedTransactions' => $groupedTransactions->has(0),
                            'isForInternal' => false,
                        'transactions' => $groupedTransactions[0]->where('category_id', null),
                        'categoryName' => __('transaction.no_category'), 
                        'typeTransactions' => 0,
                    ])
                    </div> 
                </div>
            @endif
        </div>
    </div>
    @include('public_reports.finance._footer_summary')
</div>

<!-- <div class="page-header mt-0 mb-2">
    <h2 class="page-title">{{ __('transaction.spending') }}</h2>
</div> -->
@endsection
