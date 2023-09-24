@extends('layouts.public_reports')

@section('subtitle', __('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

<div class="page-header mt-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.in_weeks', __('report.this_month'), Request::except(['year', 'month']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group mb-0">
            @livewire('prev-month-button', ['routeName' => 'public_reports.in_weeks', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-month-button', ['routeName' => 'public_reports.in_weeks', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    <table class="table table-sm card-table table-hover table-bordered">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.date') }}</th>
                <th>{{ __('transaction.transaction') }}</th>
                <th class="text-right">{{ __('transaction.income') }}</th>
                <th class="text-right">{{ __('transaction.spending') }}</th>
                <th class="text-right">{{ __('transaction.balance') }}</th>
            </tr>
        </thead>
        <tbody>
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
                                <td class="text-right text-nowrap">
                                    @php
                                        $incomeAmount = $categorizedTransactions->sum(function ($transaction) {
                                            return $transaction->in_out ? $transaction->amount : 0;
                                        });
                                    @endphp
                                    {{ $incomeAmount ? number_format($incomeAmount) : '' }}
                                </td>
                                <td class="text-right text-nowrap">
                                    @php
                                        $spendingAmount = $categorizedTransactions->sum(function ($transaction) {
                                            return !$transaction->in_out ? $transaction->amount : 0;
                                        });
                                    @endphp
                                    {{ $spendingAmount ? number_format($spendingAmount) : '' }}
                                </td>
                                <td class="text-center text-nowrap">&nbsp;</td>
                            </tr>
                        @else
                            @foreach ($categorizedTransactions as $transaction)
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
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="strong">
                <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                <td class="text-right">
                    @php
                        $incomeAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                            return $transaction->in_out ? $transaction->amount : 0;
                        });
                    @endphp
                    {{ number_format($incomeAmount, 0) }}
                </td>
                <td class="text-right">
                    @php
                        $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                            return $transaction->in_out ? 0 : $transaction->amount;
                        });
                    @endphp
                    {{ number_format($spendingAmount, 0) }}
                </td>
                <td class="text-right">{{ number_format($incomeAmount - $spendingAmount, 0) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endforeach
@endsection
