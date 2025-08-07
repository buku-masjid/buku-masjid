<div class="accordion accordion-flush">
    <div class="d-none d-sm-block">
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
            <div class="accordion-body transaction-list mb-0">
                @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
                    <div class="row py-2 fs-4">
                        <div class="col-auto d-flex align-items-center">
                            <i class="d-sm-none ti icon fe-bold bm-txt-primary">&#xea13;</i>
                            <span class="d-none d-sm-block">{{ ++$key }} </span>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col col-cat">
                                    {{ $incomeCategory->name }}
                                </div>
                                <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold text-start text-sm-end">
                                    @if ($groupedTransactions->has(1))
                                        {{ format_number($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount')) }}
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold d-none d-sm-block text-start text-sm-end">
                                    -
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($groupedTransactions->has(1))
                    @foreach($groupedTransactions[1]->where('category_id', null) as $transaction)
                        <div class="row py-2 fs-3">
                            <div class="col-auto d-flex align-items-center">
                                <i class="d-sm-none ti icon fe-bold bm-txt-primary">&#xea13;</i>
                                <span class="d-none d-sm-block">{{ ++$key }} </span>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col col-cat">
                                        {!! $transaction->date_alert !!} {{ $transaction->description }}
                                    </div>
                                    <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold text-start text-sm-end">
                                        {{ format_number($transaction->amount) }}
                                    </div>
                                    <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold d-none d-sm-block text-start text-sm-end">
                                        -
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="accordion-item card mb-2">
        <div class="accordion-header">
            <button class="accordion-button collapsed fs-2 fw-bold bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#outcome" aria-expanded="false">
               {{ __('transaction.spending') }}
            </button>
        </div>
        @php
            $key = 0;
        @endphp
        <div id="outcome" class="px-3 pb-3 accordion-collapse collapse">
            <div class="accordion-body transaction-list-out mb-0">
                @foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
                    <div class="row py-2 fs-4">
                        <div class="col-auto d-flex align-items-center">
                            <i class="d-sm-none ti icon fe-bold bm-txt-out">&#xea24;</i>
                            <span class="d-none d-sm-block">{{ ++$key }} </span>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col col-cat">
                                    {{ $spendingCategory->name }}
                                </div>
                                <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold d-none d-sm-block text-start text-sm-end">
                                    -
                                </div>
                                <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold text-start text-sm-end">
                                    @if ($groupedTransactions->has(0))
                                        {{ format_number($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                                    @else
                                        0
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($groupedTransactions->has(0))
                    @foreach($groupedTransactions[0]->where('category_id', null) as $transaction)
                        <div class="row py-2 fs-3">
                            <div class="col-auto d-flex align-items-center">
                                <i class="d-sm-none ti icon fe-bold bm-txt-out">&#xea24;</i>
                                <span class="d-none d-sm-block">{{ ++$key }} </span>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col col-cat">
                                        {!! $transaction->date_alert !!} {{ $transaction->description }}
                                    </div>
                                    <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold d-none d-sm-block text-start text-sm-end">
                                        -
                                    </div>
                                    <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold text-start text-sm-end">
                                        {{ format_number($transaction->amount) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @include('public_reports.finance._footer_summary')
</div>

<!-- TABLE
<table class="table table-sm table-hover table-bordered">
    <thead>
        <tr>
            <th style="min-width: 3em" class="text-center">{{ __('app.table_no') }}</th>
            <th style="min-width: 25em">{{ __('transaction.transaction') }}</th>
            <th class="text-end">{{ __('transaction.income') }}</th>
            <th class="text-end">{{ __('transaction.spending') }}</th>
            <th class="text-end">{{ __('transaction.balance') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ($lastMonthBalance || auth()->activeBook()->bank_account_id)
            <tr><td colspan="5">{{ __('transaction.balance') }}</td></tr>
        @endif
        @if (auth()->activeBook()->bank_account_id)
            <tr>
                <td class="text-center">1</td>
                <td>Saldo per {{ Carbon\Carbon::parse($lastBankAccountBalanceOfTheMonth->date)->isoFormat('D MMMM Y') }} di BANK</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end text-nowrap">{{ format_number($lastBankAccountBalanceOfTheMonth->amount) }}</td>
            </tr>
        @endif
        @if ($lastMonthBalance)
            <tr>
                <td class="text-center">
                    {{ auth()->activeBook()->bank_account_id ? '2' : '1' }}
                </td>
                <td>Sisa saldo per {{ $lastMonthDate->isoFormat('D MMMM Y') }}</td>
                <td class="text-end text-nowrap">&nbsp;</td>
                <td class="text-center text-nowrap">&nbsp;</td>
                <td class="text-end text-nowrap">{{ format_number($lastMonthBalance) }}</td>
            </tr>
        @endif
        <tr><td colspan="5">{{ __('transaction.income') }}</td></tr>
        @php
            $key = 0;
        @endphp
        @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
        <tr>
            <td class="text-center">{{ ++$key }}</td>
            <td>{{ $incomeCategory->name }}</td>
            <td class="text-end text-nowrap">
                @if ($groupedTransactions->has(1))
                    {{ format_number($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount')) }}
                @else
                    0
                @endif
            </td>
            <td class="text-end text-nowrap">-</td>
            <td class="text-center text-nowrap">&nbsp;</td>
        </tr>
        @endforeach
        @if ($groupedTransactions->has(1))
            @foreach($groupedTransactions[1]->where('category_id', null) as $transaction)
            <tr>
                <td class="text-center">{{ ++$key }}</td>
                <td>{!! $transaction->date_alert !!} {{ $transaction->description }}</td>
                <td class="text-end text-nowrap">{{ format_number($transaction->amount) }}</td>
                <td class="text-end text-nowrap">-</td>
                <td class="text-center text-nowrap">&nbsp;</td>
            </tr>
            @endforeach
        @endif

        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">{{ __('transaction.spending') }}</td></tr>
        @foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
        <tr>
            <td class="text-center">{{ ++$key }}</td>
            <td>{{ $spendingCategory->name }}</td>
            <td class="text-end text-nowrap">-</td>
            <td class="text-end text-nowrap">
                @if ($groupedTransactions->has(0))
                    {{ format_number($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                @else
                    0
                @endif
            </td>
            <td class="text-center text-nowrap">&nbsp;</td>
        </tr>
        @endforeach
        @if ($groupedTransactions->has(0))
            @foreach($groupedTransactions[0]->where('category_id', null) as $transaction)
            <tr>
                <td class="text-center">{{ ++$key }}</td>
                <td>{!! $transaction->date_alert !!} {{ $transaction->description }}</td>
                <td class="text-end text-nowrap">-</td>
                <td class="text-end text-nowrap">{{ format_number($transaction->amount) }}</td>
                <td class="text-center text-nowrap">&nbsp;</td>
            </tr>
            @endforeach
        @endif
        <tr><td colspan="5">&nbsp;</td></tr>
    </tbody>
    @if (!$groupedTransactions->isEmpty())
    <tfoot>
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="text-end">
                {{ __('transaction.in_out') }} hingga {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}
            </td>
            <td class="text-end">
                @php
                    $currentMonthIncome = $groupedTransactions->has(1) ? $groupedTransactions[1]->sum('amount') : 0;
                @endphp
                {{ format_number($currentMonthIncome) }}
            </td>
            <td class="text-end">
                @php
                    $currentMonthSpending = $groupedTransactions->has(0) ? $groupedTransactions[0]->sum('amount') : 0;
                @endphp
                {{ format_number($currentMonthSpending) }}
            </td>
            <td class="text-end text-nowrap">
                @php
                    $currentMonthBalance = $currentMonthIncome - $currentMonthSpending;
                @endphp
                {{ format_number($currentMonthBalance) }}
            </td>
        </tr>
        @if (auth()->activeBook()->bank_account_id)
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="text-end">Saldo Kas hingga per {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end text-nowrap">
                @php
                    $currentMonthBalance = $lastMonthBalance + $currentMonthIncome - $currentMonthSpending;
                @endphp
                {{ format_number($currentMonthBalance) }}
            </td>
        </tr>
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="text-end">Saldo Kas + Saldo bank per {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end text-nowrap">
                {{ format_number($currentMonthBalance + $lastBankAccountBalanceOfTheMonth->amount) }}
            </td>
        </tr>
        @else
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="text-center">Total saldo akhir per {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end">&nbsp;</td>
            <td class="text-end text-nowrap">
                @php
                    $currentMonthBalance = $lastMonthBalance + $currentMonthIncome - $currentMonthSpending;
                @endphp
                {{ format_number($currentMonthBalance) }}
            </td>
        </tr>
        @endif
    </tfoot>
    @endif
</table> -->


