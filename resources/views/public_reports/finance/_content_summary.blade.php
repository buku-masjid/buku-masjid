<div class="accordion accordion-flush">
    <div class="d-none d-sm-block">
        <div class="row pe-5 py-3" style="padding-left: 1.25rem">
            <div class="col-auto d-none"></div>
            <div class="col bm-fade fs-3 fw-bold rounded">{{ __('transaction.transaction') }}</div>
            <div class="col-2 p-0 bm-fade fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.income') }}</div>
            <div class="col-2 p-0 bm-fade fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.spending') }}</div>
        </div>
    </div>
    @foreach ($groupedTransactions as $weekNumber => $transactionsByCategoryId)
        <div class="accordion-item card mb-2">
            <div class="accordion-header">
                <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#week_number_{{ 1 + $weekNumber }}" aria-expanded="false">
                    {{ __('time.week') }} {{ $weekNumber + 1 }} &nbsp;<span class="text-dark small">({{ $weekLabels[$weekNumber] }})</span>
                </button>
            </div>
            <div id="week_number_{{ 1 + $weekNumber }}" class="pe-lg-4 accordion-collapse collapse {{ $weekNumber == $groupedTransactions->keys()->first() ? 'show' : '' }}">
                <div class="accordion-body pt-0">
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
                                            @if ($firstTrasaction->in_out)
                                                <i class="ti icon fe-bold bm-txt-primary">&#xea13;</i>
                                            @else
                                                <i class="ti icon fe-bold bm-txt-out">&#xea24;</i>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col col-cat">{{ $category ? $category->name : __('category.uncategorized') }}</div>
                                                <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold">
                                                    {{ ($firstTrasaction->in_out) ? config('money.currency_code') : '' }} {{ ($firstTrasaction->in_out) ? format_number($transactions->sum('amount')) : '' }}
                                                </div>
                                                <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold">
                                                    {{ ($firstTrasaction->in_out) ? '' : config('money.currency_code') }} {{ ($firstTrasaction->in_out) ? '' : format_number($transactions->sum('amount')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (optional($category)->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_PUBLIC || is_null($category))
                                    <div id="week_category_{{ 1 + $weekNumber }}_{{ $categoryId }}" class="accordion-collapse collapse">
                                        <div class="accordion-body {{ ($firstTrasaction->in_out) ? 'transaction-list' : 'transaction-list-out' }} mb-0">
                                            @foreach ($transactions as $transaction)
                                                <div class="row py-2 py-lg-0">
                                                    <div class="col-auto py-lg-2 date align-items-center d-flex">{{ $transaction->date }}</div>
                                                    <div class="col-lg me-1 py-lg-2">
                                                        @if ($isTransactionFilesVisible)
                                                            <span class="float-end">
                                                                @livewire('public-books.files-indicator', ['transaction' => $transaction])
                                                            </span>
                                                        @endif
                                                        {!! nl2br(htmlentities($transaction->description)) !!}
                                                    </div>
                                                    <div class="col-lg-2 py-lg-2 px-lg-0 bm-txt-primary col-num">
                                                    {{ ($firstTrasaction->in_out) ? config('money.currency_code') : '' }}{{ ($transaction->in_out) ? format_number($transaction->amount) : '' }}
                                                    </div>
                                                    <div class="col-lg-2 py-lg-2 px-lg-0 bm-txt-out col-num">
                                                    {{ ($firstTrasaction->in_out) ? '' : config('money.currency_code') }}{{ ($transaction->in_out) ? '' : format_number($transaction->amount) }}
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
