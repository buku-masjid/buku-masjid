@extends('layouts.print')

@section('title', __('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content')
{{-- ref: https://github.com/niklasravnsborg/laravel-pdf#headers-and-footers --}}
<htmlpageheader name="wpHeader">
    <h2 class="text-center strong" style="margin: 1em 0">
        @if (isset(auth()->activeBook()->report_titles['in_out']))
            {{ auth()->activeBook()->report_titles['in_out'] }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            {{ __('report.categorized_transactions') }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @endif
    </h2>
</htmlpageheader>

<h2>{{ __('transaction.income') }}</h2>

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
{{-- ref: https://mpdf.github.io/paging/page-breaks.html#tables --}}
<div style="page-break-inside: avoid">
    <h4>{{ $incomeCategory->name }}</h4>
    <div>
        <table class="table">
            <thead>
                <tr >
                    <th style="width: 5%" class="text-center">{{ __('app.table_no') }}</th>
                    <th style="width: 15%" class="text-center">{{ __('time.date') }}</th>
                    <th style="width: 60%">{{ __('app.description') }}</th>
                    <th style="width: 20%" class="text-nowrap text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            @if ($groupedTransactions->has(1))
            <tbody>
                @php
                    $key = 0;
                @endphp
                @foreach ($groupedTransactions[1]->where('category_id', $incomeCategory->id) as $transaction)
                <tr>
                    <td class="text-center ">{{ ++$key }}</td>
                    <td class="text-center ">{{ $transaction->date }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td class="text-right ">{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">{{ __('app.total') }} {{ $incomeCategory->name }}</th>
                    <th class="text-right">
                        {{ number_format($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount'), 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endforeach

<pagebreak />

<h2>{{ __('transaction.spending') }}</h2>

@foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
<div style="page-break-inside: avoid">
    <h4>{{ $spendingCategory->name }}</h4>
    <div>
        <table class="table">
            <thead>
                <tr >
                    <th style="width: 5%" class="text-center ">{{ __('app.table_no') }}</th>
                    <th style="width: 15%" class="text-center ">{{ __('time.date') }}</th>
                    <th style="width: 60%">{{ __('app.description') }}</th>
                    <th style="width: 20%" class="text-nowrap text-right ">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            @if ($groupedTransactions->has(0))
            <tbody>
                @php
                    $key = 0;
                @endphp
                @foreach ($groupedTransactions[0]->where('category_id', $spendingCategory->id) as $transaction)
                <tr>
                    <td class="text-center ">{{ ++$key }}</td>
                    <td class="text-center ">{{ $transaction->date }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td class="text-right ">{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">{{ __('app.total') }} {{ $spendingCategory->name }}</th>
                    <th class="text-right">
                        {{ number_format($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount'), 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endforeach

@endsection

@section('style')
<style>
    @page {
        size: auto;
        margin-top: 100px;
        margin-bottom: 50px;
        margin-left: 50px;
        margin-right: 50px;
        margin-header: 40px;
        margin-footer: 40px;
        header: html_wpHeader;
    }
</style>
@endsection
