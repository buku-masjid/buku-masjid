<div class="accordion accordion-flush">
    @foreach ($groupedTransactions as $weekNumber => $transactionsByCategoryId)
        <div class="accordion-item card mb-2">
            <div class="accordion-header">
                <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#week_number_{{ 1 + $weekNumber }}" aria-expanded="false">
                    Pekan {{ 1 + $weekNumber }}
                </button>
            </div>
            <div id="week_number_{{ 1 + $weekNumber }}" class="accordion-collapse collapse px-lg-4 py-lg-3 {{ $weekNumber == $groupedTransactions->keys()->first() ? 'show' : '' }}">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-auto d-none"></div>
                        <div class="col me-1 bm-fade p-2 fs-3 fw-bold rounded">{{ __('transaction.transaction') }}</div>
                        <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.income') }}</div>
                        <div class="col-2 me-1 bm-fade p-2 fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.spending') }}</div>
                    </div>
                    <div class="accordion accordion-flush transaction">
                        @foreach ($transactionsByCategoryId as $categoryId => $transactions)
                            @php
                                $firstTrasaction = $transactions->first();
                                $category = $firstTrasaction->category;
                            @endphp
                            <div class="accordion-item">
                                <div class="p-0 collapsed" data-bs-toggle="collapse" data-bs-target="#week_category_{{ 1 + $weekNumber }}_{{ $categoryId }}" aria-expanded="false">
                                    <div class="row">
                                        <div class="col-auto d-sm-none d-flex align-items-center">
                                            <i class="ti icon fe-bold bm-txt-primary">&#xea13;</i>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col col-cat">{{ $category ? $category->name : __('category.uncategorized') }}</div>
                                                <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold">
                                                    {{ ($firstTrasaction->in_out) ? format_number($transactions->sum('amount')) : '' }}
                                                </div>
                                                <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold">
                                                    {{ ($firstTrasaction->in_out) ? '' : format_number($transactions->sum('amount')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (optional($category)->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_PUBLIC || is_null($category))
                                    <div id="week_category_{{ 1 + $weekNumber }}_{{ $categoryId }}" class="accordion-collapse collapse">
                                        <div class="accordion-body transaction-list">
                                            @foreach ($transactions as $transaction)
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">{{ $transaction->date }}</div>
                                                    <div class="col-lg me-1 py-lg-2">{{ $transaction->description }}</div>
                                                    <div class="col-lg-2 py-lg-2 col-num">
                                                        {{ ($transaction->in_out) ? format_number($transaction->amount) : '' }}
                                                    </div>
                                                    <div class="col-lg-2 py-lg-2 col-num">
                                                        {{ ($transaction->in_out) ? '' : format_number($transaction->amount) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
