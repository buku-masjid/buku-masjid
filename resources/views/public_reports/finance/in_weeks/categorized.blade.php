@extends('layouts.public_reports')

@section('subtitle', __('report.finance_categorized'))

@section('content-report')

<div class="page-header my-0">
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('date_range', __('report.view_date_range_label'), ['class' => 'control-label mr-1']) }}
        {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control mr-1', 'style' => 'width:100px']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::hidden('active_book_id', request('active_book_id')) }}
            {{ Form::hidden('nonce', request('nonce')) }}
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-1']) }}
            {{ link_to_route('public_reports.finance.categorized', __('app.reset'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-secondary mr-1']) }}
        </div>
        <div class="form-group">
            @livewire('prev-week-button', ['routeName' => 'public_reports.finance.categorized', 'buttonClass' => 'btn btn-secondary mr-1'])
            @livewire('next-week-button', ['routeName' => 'public_reports.finance.categorized', 'buttonClass' => 'btn btn-secondary'])
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header mt-0 mb-2">
            <h2 class="page-title">{{ __('transaction.income') }}</h2>
        </div>

        @foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
        <h4 class="mt-0">{{ $incomeCategory->name }}</h4>
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr >
                        <th class="text-center col-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-2">{{ __('time.date') }}</th>
                        <th class=" col-4">{{ __('app.description') }}</th>
                        <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                    </tr>
                </thead>
                @if ($groupedTransactions->has(1))
                @if ($incomeCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                @else
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[1]->where('category_id', $incomeCategory->id) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $incomeCategory->name }}</td>
                        <td class="text-right">
                            {{ format_number($groupedTransactions[1]->where('category_id', $incomeCategory->id)->sum('amount')) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach

        @if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
            <h4 class="mt-0">{{ __('transaction.no_category') }}</h4>
            <div class="card table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr >
                            <th class="text-center col-1">{{ __('app.table_no') }}</th>
                            <th class="text-center col-2">{{ __('time.date') }}</th>
                            <th class=" col-4">{{ __('app.description') }}</th>
                            <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[1]->where('category_id', null) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="strong">
                            <td colspan="3" class="text-right">{{ __('app.total') }} {{ __('transaction.no_category') }}</td>
                            <td class="text-right">
                                {{ format_number($groupedTransactions[1]->where('category_id', null)->sum('amount')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <div class="page-header mt-0 mb-2">
            <h2 class="page-title">{{ __('transaction.spending') }}</h2>
        </div>

        @foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
        <h4 class="mt-0">{{ $spendingCategory->name }}</h4>
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr >
                        <th class="text-center col-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-2">{{ __('time.date') }}</th>
                        <th class=" col-4">{{ __('app.description') }}</th>
                        <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                    </tr>
                </thead>
                @if ($groupedTransactions->has(0))
                @if ($spendingCategory->report_visibility_code == App\Models\Category::REPORT_VISIBILITY_INTERNAL)
                    <tbody>
                        <tr>
                            <td class="text-center col-1">&nbsp;</td>
                            <td class="text-center col-2">&nbsp;</td>
                            <td class="col-4">{{ $spendingCategory->name }}</td>
                            <td class="text-right col-3">
                                {{ format_number($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                            </td>
                        </tr>
                    </tbody>
                @else
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[0]->where('category_id', $spendingCategory->id) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }} {{ $spendingCategory->name }}</td>
                        <td class="text-right">
                            {{ format_number($groupedTransactions[0]->where('category_id', $spendingCategory->id)->sum('amount')) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endforeach

        @if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
            <h4 class="mt-0">{{ __('transaction.no_category') }}</h4>
            <div class="card table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr >
                            <th class="text-center col-1">{{ __('app.table_no') }}</th>
                            <th class="text-center col-2">{{ __('time.date') }}</th>
                            <th class=" col-4">{{ __('app.description') }}</th>
                            <th class="text-nowrap text-right col-3">{{ __('transaction.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($groupedTransactions[0]->where('category_id', null) as $transaction)
                        <tr>
                            <td class="text-center col-1">{{ ++$key }}</td>
                            <td class="text-center col-2">{{ $transaction->date }}</td>
                            <td class="col-4">{{ $transaction->description }}</td>
                            <td class="text-right col-3">{{ format_number($transaction->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="strong">
                            <td colspan="3" class="text-right">{{ __('app.total') }} {{ __('transaction.no_category') }}</td>
                            <td class="text-right">
                                {{ format_number($groupedTransactions[0]->where('category_id', null)->sum('amount')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
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
