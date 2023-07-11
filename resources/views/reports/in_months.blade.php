@extends('layouts.reports')

@section('subtitle', __('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

<div class="page-header mt-0">
    <h1 class="page-title">{{ __('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]) }}</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.in_months', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-month-button', ['routeName' => 'reports.in_months', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'reports.in_months', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="card table-responsive">
    <table class="table table-sm card-table table-hover table-bordered">
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
            <tr>
                <td class="text-center">1</td>
                <td>Saldo per {{ Carbon\Carbon::parse($lastBankAccountBalanceOfTheMonth->date)->isoFormat('D MMMM Y') }} di BANK</td>
                <td>-</td>
                <td>-</td>
                <td class="text-right text-nowrap">{{ number_format($lastBankAccountBalanceOfTheMonth->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
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
                <th class="text-center">Selisih saldo {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</th>
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
                    {{ number_format($currentMonthBalance, 0) }}
                </th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <th class="text-center">Total saldo akhir per {{ $currentMonthEndDate->isoFormat('D MMMM Y') }}</th>
                <th>-</th>
                <th>-</th>
                <th class="text-right">
                    {{ number_format($currentMonthBalance + $lastBankAccountBalanceOfTheMonth->amount, 0) }}
                </th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection
