@extends('layouts.print')

@section('title', __('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content')
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
        @if (isset(auth()->activeBook()->report_titles['in_months']))
            {{ auth()->activeBook()->report_titles['in_months'] }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            {{ __('report.monthly') }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @endif
    </h2>
</htmlpageheader>

<div class="">
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th>{{ __('transaction.transaction') }}</th>
                <th class="text-right">{{ __('transaction.income') }}</th>
                <th class="text-right">{{ __('transaction.spending') }}</th>
                <th class="text-right">{{ __('transaction.balance') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="5">{{ __('transaction.balance') }}</td></tr>
            @if (auth()->activeBook()->bank_account_id)
            <tr>
                <td class="text-center">1</td>
                <td>Saldo per {{ Carbon\Carbon::parse($lastBankAccountBalanceOfTheMonth->date)->isoFormat('D MMMM Y') }} di BANK</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right text-nowrap">{{ number_format($lastBankAccountBalanceOfTheMonth->amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="text-center">
                    {{ auth()->activeBook()->bank_account_id ? '2' : '1' }}
                </td>
                <td>Sisa saldo per {{ $lastMonthDate->isoFormat('D MMMM Y') }}</td>
                <td class="text-right text-nowrap">{{ number_format($lastMonthBalance) }}</td>
                <td class="text-right text-nowrap">-</td>
                <td class="text-center text-nowrap">&nbsp;</td>
            </tr>
            <tr><td colspan="5">{{ __('transaction.income') }}</td></tr>
            @php
                $key = 0;
            @endphp
            @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
            <tr>
                <td class="text-center">{{ ++$key }}</td>
                <td>{{ $incomeCategory->name }}</td>
                <td class="text-right text-nowrap">
                    @if ($groupedTransactions->has(1))
                        {{ number_format($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount'), 0) }}
                    @else
                        0
                    @endif
                </td>
                <td class="text-right text-nowrap">-</td>
                <td class="text-center text-nowrap">&nbsp;</td>
            </tr>
            @endforeach
            @if ($groupedTransactions->has(1))
                @foreach($groupedTransactions[1]->where('category_id', null) as $transaction)
                <tr>
                    <td class="text-center">{{ ++$key }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td class="text-right text-nowrap">{{ number_format($transaction->amount, 0) }}</td>
                    <td class="text-right text-nowrap">-</td>
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
                <td class="text-right text-nowrap">-</td>
                <td class="text-right text-nowrap">
                    @if ($groupedTransactions->has(0))
                        {{ number_format($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount'), 0) }}
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
                    <td>{{ $transaction->description }}</td>
                    <td class="text-right text-nowrap">{{ number_format($transaction->amount, 0) }}</td>
                    <td class="text-right text-nowrap">-</td>
                    <td class="text-center text-nowrap">&nbsp;</td>
                </tr>
                @endforeach
            @endif
            <tr><td colspan="5">&nbsp;</td></tr>
        </tbody>
        @if (!$groupedTransactions->isEmpty())
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <th class="text-center">
                    {{ auth()->activeBook()->bank_account_id ? 'Selisih' : '' }} Saldo {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}
                </th>
                <th class="text-right">
                    @php
                        $currentMonthIncome = $groupedTransactions->has(1) ? $groupedTransactions[1]->sum('amount') : 0;
                    @endphp
                    {{ number_format($lastMonthBalance + $currentMonthIncome, 0) }}
                </th>
                <th class="text-right">
                    @php
                        $currentMonthSpending = $groupedTransactions->has(0) ? $groupedTransactions[0]->sum('amount') : 0;
                    @endphp
                    {{ number_format($currentMonthSpending, 0) }}
                </th>
                <th class="text-right">
                    @php
                        $currentMonthBalance = $lastMonthBalance + $currentMonthIncome - $currentMonthSpending;
                    @endphp
                    {{ number_format($currentMonthBalance, 2) }}
                </th>
            </tr>
            @if (auth()->activeBook()->bank_account_id)
            <tr>
                <td>&nbsp;</td>
                <th class="text-center">Total saldo akhir per {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</th>
                <th class="text-right">-</th>
                <th class="text-right">-</th>
                <th class="text-right">
                    {{ number_format($currentMonthBalance + $lastBankAccountBalanceOfTheMonth->amount, 2) }}
                </th>
            </tr>
            @endif
        </tfoot>
        @endif
    </table>
</div>
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
    }
</style>
@endsection
