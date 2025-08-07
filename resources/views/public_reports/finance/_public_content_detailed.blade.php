@foreach ($weekTransactions as $dayName => $daysTransactions)
    @if ($dayName)
        <div class="px-3 pb-2 pt-2 fw-bold ">
            <span class="ti icon pe-2">&#xea52;</span><span>{{ strtoupper($dayName) }}</span>
        </div>
    @endif
    @foreach ($daysTransactions->groupBy('category.report_visibility_code') as $categoryVisibility => $visibilityCategorizedTransactions)
        @foreach ($visibilityCategorizedTransactions->groupBy('category.name') as $categoryName => $categorizedTransactions)
            @if ($categoryVisibility == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                <div class="row pe-4 py-2 g-0" style="padding-left: 1.25rem">
                    <div class="col-auto d-none"></div>
                    <div class="col fs-3 fw-bold rounded">{{ $categorizedTransactions->first()->date }} {{ $categoryName }}</div>
                    <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">
                        @php
                            $incomeAmount = $categorizedTransactions->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : 0;
                            });
                        @endphp
                        {{ $incomeAmount ? format_number($incomeAmount) : '' }}
                    </div>
                    <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">
                        @php
                            $spendingAmount = $categorizedTransactions->sum(function ($transaction) {
                                return !$transaction->in_out ? $transaction->amount : 0;
                            });
                        @endphp
                        {{ $spendingAmount ? format_number($spendingAmount) : '' }}
                    </div>
                    <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">&nbsp;</div>
                </div>
            @else
                @foreach ($categorizedTransactions as $transaction)
                    <div class="row g-0 accordion-body transaction-list{{ $transaction->in_out ? '' : '-out' }}" style="padding: 8px 0 !important; margin: 5px 0 !important;">
                        <div class="col-auto d-none"></div>
                        <div class="col fs-4 ps-3 fw-bold rounded">
                            <span class="text-secondary fs-5 pe-2 d-block d-lg-inline">{{ $categorizedTransactions->first()->date }} </span>
                            @if ($isTransactionFilesVisible)
                                <span class="float-end">
                                    @livewire('public-books.files-indicator', ['transaction' => $transaction])
                                </span>
                            @endif
                            {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                        </div>
                        <div class="ps-3 ps-lg-0 col-lg-2 p-0 fs-3 fw-bold rounded text-lg-end">
                            {{ $transaction->in_out ? format_number($transaction->amount) : '-' }}
                        </div>
                        <div class="ps-3 ps-lg-0 col-lg-2 p-0 fs-3 fw-bold rounded text-lg-end">
                            {{ !$transaction->in_out ? format_number($transaction->amount) : '-' }}
                        </div>
                        <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">&nbsp;</div>
                    </div>
                @endforeach
            @endif
        @endforeach
    @endforeach
    <div class="d-none d-lg-block">
        <div class="row g-0 accordion-body bg-light border-top border-bottom border-1" style="padding: 8px 0 !important; margin: 5px 0 !important;">
            <div class="col-auto d-none"></div>
            <div class="col fs-4 ps-3 fw-bold rounded">Total</div>
            <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">
                @php
                    $incomeAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? $transaction->amount : 0;
                    });
                @endphp
                {{ format_number($incomeAmount) }}
            </div>
            <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">
                @php
                    $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? 0 : $transaction->amount;
                    });
                @endphp
                {{ format_number($spendingAmount) }}
            </div>
            <div class="col-2 p-0 fs-3 fw-bold rounded text-end pe-2">{{ format_number($incomeAmount - $spendingAmount) }}</div>
        </div>
    </div>
@endforeach
<div class="d-flex justify-content-start justify-content-lg-end p-3">
    <div class="pe-5 text-start text-lg-end fw-bold d-none d-lg-block">
        <span class="fs-5 text-secondary">{{ 'Saldo '.$lastWeekDate->isoFormat('D / MM / Y') }}</span><br>
        <span class="fs-2 bm-txt-primary">{{ format_number($currentWeekBalance = auth()->activeBook()->getBalance($lastWeekDate->format('Y-m-d'))) }}</span>
    </div>
    <div class="p-0 fs-3 fw-bold rounded text-start text-lg-end d-lg-block bm-txt-primary">
        <span class="fs-5 text-secondary">{{ __('transaction.end_balance') }} {{ __('time.week') }} {{ $weekNumber + 1 }}</span><br>
        <span class="fs-2">{{ format_number($currentWeekBalance + $incomeAmount - $spendingAmount) }}</span>
    </div>
</div>
<!-- PREVIOUS TABLE ----------->
<!-- <table class="table table-sm mb-0 table-hover table-bordered table-responsive-sm">
     <thead>
        <tr>
            <th class="text-center">{{ __('app.date') }}</th>
            <th style="min-width: 25em">{{ __('transaction.transaction') }}</th>
            <th class="text-end">{{ __('transaction.income') }}</th>
            <th class="text-end">{{ __('transaction.spending') }}</th>
            <th class="text-end">{{ __('transaction.balance') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr class="strong">
            <td>&nbsp;</td>
            <td class="strong">{{ 'Sisa saldo per '.$lastWeekDate->isoFormat('D MMMM Y') }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="strong text-end text-nowrap">{{ format_number($currentWeekBalance = auth()->activeBook()->getBalance($lastWeekDate->format('Y-m-d'))) }}</td>
        </tr>
        @foreach ($weekTransactions as $dayName => $daysTransactions)
            @if ($dayName)
                <tr><td class="text-center strong">{{ strtoupper($dayName) }}</td><td colspan="4">&nbsp;</td></tr>
            @endif
            @foreach ($daysTransactions->groupBy('category.report_visibility_code') as $categoryVisibility => $visibilityCategorizedTransactions)
                @foreach ($visibilityCategorizedTransactions->groupBy('category.name') as $categoryName => $categorizedTransactions)
                    @if ($categoryVisibility == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                        <tr>
                            <td class="text-center">{{ $categorizedTransactions->first()->date }}</td>
                            <td>{{ $categoryName }}</td>
                            <td class="text-end text-nowrap">
                                @php
                                    $incomeAmount = $categorizedTransactions->sum(function ($transaction) {
                                        return $transaction->in_out ? $transaction->amount : 0;
                                    });
                                @endphp
                                {{ $incomeAmount ? format_number($incomeAmount) : '' }}
                            </td>
                            <td class="text-end text-nowrap">
                                @php
                                    $spendingAmount = $categorizedTransactions->sum(function ($transaction) {
                                        return !$transaction->in_out ? $transaction->amount : 0;
                                    });
                                @endphp
                                {{ $spendingAmount ? format_number($spendingAmount) : '' }}
                            </td>
                            <td class="text-center text-nowrap">&nbsp;</td>
                        </tr>
                    @else
                        @foreach ($categorizedTransactions as $transaction)
                        <tr class="{{ $transaction->is_strong ? 'strong' : '' }}">
                            <td class="text-center">{{ $transaction->date }}</td>
                            <td {{ $transaction->is_strong ? 'style=text-decoration:underline' : '' }}>
                                @if ($isTransactionFilesVisible)
                                    <span class="float-end">
                                        @livewire('public-books.files-indicator', ['transaction' => $transaction])
                                    </span>
                                @endif
                                {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                            </td>
                            <td class="text-end text-nowrap">{{ $transaction->in_out ? format_number($transaction->amount) : '' }}</td>
                            <td class="text-end text-nowrap">{{ !$transaction->in_out ? format_number($transaction->amount) : '' }}</td>
                            <td class="text-center text-nowrap">&nbsp;</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="strong">
            <td colspan="2" class="text-end">{{ __('transaction.in_out') }} {{ __('time.week') }} {{ $weekNumber + 1 }}</td>
            <td class="text-end">
                @php
                    $incomeAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? $transaction->amount : 0;
                    });
                @endphp
                {{ format_number($incomeAmount) }}
            </td>
            <td class="text-end">
                @php
                    $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? 0 : $transaction->amount;
                    });
                @endphp
                {{ format_number($spendingAmount) }}
            </td>
            <td class="text-end text-nowrap">{{ format_number($incomeAmount - $spendingAmount) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="text-end strong">{{ __('transaction.end_balance') }} {{ __('time.week') }} {{ $weekNumber + 1 }}</td>
            <td class="text-end strong">&nbsp;</td>
            <td class="text-end strong">&nbsp;</td>
            <td class="text-end strong text-nowrap">{{ format_number($currentWeekBalance + $incomeAmount - $spendingAmount) }}</td>
        </tr>
    </tfoot>
</table> -->
