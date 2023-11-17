@extends('layouts.public_reports')

@section('subtitle', __('report.finance_detailed'))

@section('content-report')

<div class="page-header mt-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.finance.detailed', __('app.reset'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-week-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-week-button', ['routeName' => 'public_reports.finance.detailed', 'buttonClass' => 'btn btn-secondary'])
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
                                    {{ $incomeAmount ? format_number($incomeAmount) : '' }}
                                </td>
                                <td class="text-right text-nowrap">
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
                                    {{ $transaction->description }}
                                </td>
                                <td class="text-right text-nowrap">{{ $transaction->in_out ? format_number($transaction->amount) : '' }}</td>
                                <td class="text-right text-nowrap">{{ !$transaction->in_out ? format_number($transaction->amount) : '' }}</td>
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
                    {{ format_number($incomeAmount) }}
                </td>
                <td class="text-right">
                    @php
                        $spendingAmount = $weekTransactions->flatten()->sum(function ($transaction) {
                            return $transaction->in_out ? 0 : $transaction->amount;
                        });
                    @endphp
                    {{ format_number($spendingAmount) }}
                </td>
                <td class="text-right">{{ format_number($incomeAmount - $spendingAmount) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endforeach
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#reportModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        scrollMonth: false,
    });
})();
</script>
@endpush
