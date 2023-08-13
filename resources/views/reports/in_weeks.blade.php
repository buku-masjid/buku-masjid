@extends('layouts.reports')

@section('subtitle', __('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]))

@section('content-report')

@if (request('action') && request('book_id') && request('nonce'))
    <div id="reportModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.change_report_title') }}</h5>
                    {{ link_to_route('reports.in_weeks', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['books.report_titles.update', request('book_id')], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @php
                        $existingReportTitle = __('report.weekly');
                        if (isset(auth()->activeBook()->report_titles['in_weeks'])) {
                            $existingReportTitle = auth()->activeBook()->report_titles['in_weeks'];
                        }
                        $reportTitle = old('report_titles[in_weeks]', $existingReportTitle);
                    @endphp
                    {{ Form::text('report_titles[in_weeks]', $reportTitle, [
                        'required' => true,
                        'class' => 'form-control',
                    ]) }}
                    {{ Form::hidden('book_id', request('book_id')) }}
                    {{ Form::hidden('nonce', request('nonce')) }}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.change_report_title'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('reports.in_weeks', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    {!! Form::submit(__('book.reset_report_title'), ['class' => 'btn btn-secondary', 'name' => 'reset_report_title[in_weeks]']) !!}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif

<div class="page-header mt-0">
    <h1 class="page-title mb-4">
        @if (isset(auth()->activeBook()->report_titles['in_weeks']))
            {{ auth()->activeBook()->report_titles['in_weeks'] }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @else
            {{ __('report.weekly') }} - {{ $currentMonthEndDate->isoFormat('MMMM Y') }}
        @endif

        @can('update', auth()->activeBook())
            {{ link_to_route(
                'reports.in_weeks',
                __('book.change_report_title'),
                request()->all() + ['action' => 'change_report_title', 'book_id' => auth()->activeBook()->id, 'nonce' => auth()->activeBook()->nonce],
                ['class' => 'btn btn-success btn-sm', 'id' => 'change_report_title']
            ) }}
        @endcan
    </h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('month', __('report.view_monthly_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('reports.in_weeks', __('report.this_month'), [], ['class' => 'btn btn-secondary mr-1']) }}
            {{ link_to_route('reports.in_weeks_pdf', __('report.export_pdf'), ['year' => $year, 'month' => $month], ['class' => 'btn btn-secondary mr-1']) }}
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
                    <td class="text-right text-nowrap">{{ $transaction->in_out ? number_format($transaction->amount) : '' }}</td>
                    <td class="text-right text-nowrap">{{ !$transaction->in_out ? number_format($transaction->amount) : '' }}</td>
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

@push('scripts')
<script>
(function () {
    $('#reportModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
