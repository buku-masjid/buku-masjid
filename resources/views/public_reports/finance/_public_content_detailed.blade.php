<table class="table table-sm mb-0 table-hover table-bordered table-responsive-sm">
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
</table>
