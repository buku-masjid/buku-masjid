@extends('layouts.reports')

@section('subtitle', __('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

<div class="page-header mt-0">
    <h1 class="page-title">{{ __('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]) }}</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.in_weeks', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-month-button', ['routeName' => 'reports.in_weeks', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'reports.in_weeks', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    <table class="table table-sm card-table table-hover table-bordered">
        <thead>
            <th class="text-center">{{ __('app.date') }}</th>
            <th>{{ __('transaction.transaction') }}</th>
            <th class="text-right">{{ __('transaction.income') }}</th>
            <th class="text-right">{{ __('transaction.spending') }}</th>
            <th class="text-right">{{ __('transaction.balance') }}</th>
        </thead>
        <tbody>
            @foreach ($weekTransactions as $dayName => $daysTransactions)
                @if ($dayName)
                    <tr><td class="text-center strong">{{ strtoupper($dayName) }}</td><td colspan="4">&nbsp;</td></tr>
                @endif
                @foreach ($daysTransactions as $transaction)
                <tr class="{{ $transaction->is_strong ? 'strong' : '' }}">
                    <td class="text-center">{{ $transaction->date }}</td>
                    <td {{ $transaction->is_strong ? 'style=text-decoration:underline' : '' }}>
                        {{ $transaction->description }}
                    </td>
                    <td class="text-right text-nowrap">{{ $transaction->in_out ? number_format($transaction->amount) : '' }}</td>
                    <td class="text-right text-nowrap">{{ !$transaction->in_out ? number_format($transaction->amount) : '' }}</td>
                    <td class="text-center text-nowrap">&nbsp;</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <th colspan="2" class="text-right">{{ __('app.total') }}</th>
            <th class="text-right">
                @php
                    $incomeAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? $transaction->amount : 0;
                    });
                @endphp
                {{ number_format($incomeAmount, 0) }}
            </th>
            <th class="text-right">
                @php
                    $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                        return $transaction->in_out ? 0 : $transaction->amount;
                    });
                @endphp
                {{ number_format($spendingAmount, 0) }}
            </th>
            <th class="text-right">{{ number_format($incomeAmount - $spendingAmount, 0) }}</th>
        </tfoot>
    </table>
</div>
@endforeach
@endsection
