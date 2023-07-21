@extends('layouts.app')

@section('title', __('transaction.search'))

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('transaction.search') }}</h1>
    <div class="page-subtitle">{{ $transactions->count() }} {{ __('transaction.transaction') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('transactions.index', __('transaction.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                    {{ Form::text('search_query', request('search_query'), ['class' => 'form-control form-control-sm mr-2 mt-4 mt-sm-0', 'placeholder' => __('transaction.search_text')]) }}
                    {{ Form::text('start_date', $startDate, ['class' => 'form-control form-control-sm mr-2 mt-4 mt-sm-0 date-select', 'style' => 'width:100px', 'placeholder' => __('time.start_date')]) }}
                    {{ Form::text('end_date', $endDate, ['class' => 'form-control form-control-sm mr-2 mt-4 mt-sm-0 date-select', 'style' => 'width:100px', 'placeholder' => __('time.end_date')]) }}
                    {{ Form::select('category_id', $categories, request('category_id'), ['placeholder' => __('category.all'), 'class' => 'form-control form-control-sm mr-2 mt-4 mt-sm-0', ]) }}
                    <div class="form-group mt-4 mt-sm-0">
                        {{ Form::submit(__('app.search'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
                        {{ link_to_route('transaction_search.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                    </div>
                {{ Form::close() }}
            </div>
            @if ($searchQuery)
                @desktop
                <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">{{ __('app.table_no') }}</th>
                            <th class="col-md-2">{{ __('app.date') }}</th>
                            <th class="col-md-7">{{ __('transaction.description') }}</th>
                            <th class="text-right col-md-2">{{ __('transaction.amount') }}</th>
                            <th class="text-center">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $transaction)
                        <tr>
                            <td class="text-center">{{ 1 + $key }}</td>
                            <td>{{ $transaction->date }} ({{ $transaction->day_name }})</td>
                            <td>
                                {{ $transaction->description }}
                                <div class="float-right">
                                    @if ($transaction->category)
                                        @php
                                            $categoryRoute = route('categories.show', [
                                                $transaction->category_id,
                                                'start_date' => $startDate,
                                                'end_date' => $endDate,
                                            ]);
                                        @endphp
                                        <a href="{{ $categoryRoute }}">{!! $transaction->category->name_label !!}</a>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">{{ $transaction->amount_string }}</td>
                            <td class="text-center text-nowrap">
                                {{ link_to_route('transactions.index', __('app.show'), [
                                    'query' => $searchQuery,
                                    'date' => $transaction->date_only,
                                    'month' => $transaction->month,
                                    'year' => $transaction->year,
                                ], ['class' => 'btn btn-secondary btn-sm']) }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @elsedesktop
                <div class="card-body">
                    @foreach ($transactions as $transaction)
                        @include('transaction_search.partials.single_transaction_mobile', ['transaction' => $transaction])
                    @endforeach
                </div>
                @enddesktop
            @endif
        </div>
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
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
})();
</script>
@endpush
