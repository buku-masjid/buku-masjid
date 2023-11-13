@extends('layouts.reports')

@section('subtitle', __('report.finance_detailed'))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    <div id="reportModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.change_report_title') }}</h5>
                    {{ link_to_route('reports.finance.detailed', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['books.report_titles.update', request('book_id')], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @php
                        $existingReportTitle = __('report.finance_detailed');
                        if (isset(auth()->activeBook()->report_titles['finance_detailed'])) {
                            $existingReportTitle = auth()->activeBook()->report_titles['finance_detailed'];
                        }
                        $reportTitle = old('report_titles[finance_detailed]', $existingReportTitle);
                    @endphp
                    {{ Form::text('report_titles[finance_detailed]', $reportTitle, [
                        'required' => true,
                        'class' => 'form-control',
                    ]) }}
                    {{ Form::hidden('book_id', request('book_id')) }}
                    {{ Form::hidden('nonce', request('nonce')) }}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.change_report_title'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('reports.finance.detailed', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    {!! Form::submit(__('book.reset_report_title'), ['class' => 'btn btn-secondary', 'name' => 'reset_report_title[finance_detailed]']) !!}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['finance_detailed']))
            {{ auth()->activeBook()->report_titles['finance_detailed'] }}
        @else
            {{ __('report.finance_detailed') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.finance.detailed',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.finance.detailed', __('app.reset'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.finance.detailed_pdf', __('report.export_pdf'), ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')], ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-week-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-week-button', ['routeName' => 'reports.finance.detailed', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

@foreach($groupedTransactions as $weekNumber => $weekTransactions)
<div class="card table-responsive">
    <table class="table table-sm mb-0 table-hover table-bordered">
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
                @foreach ($daysTransactions as $transaction)
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
