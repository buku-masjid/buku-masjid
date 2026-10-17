@extends('layouts.app')

@section('title', __('transaction.search'))

@section('content')

<div class="page-header">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">{{ __('transaction.search') }}</h2>
            <div class="text-secondary mt-1">{{ $transactions->count() }} {{ __('transaction.transaction') }}</div>
        </div>
        <div class="col-auto text-end">
            {{ link_to_route('transactions.index', __('transaction.back_to_index'), [], ['class' => 'btn']) }}
        </div>
    </div>
</div>

<div class="page-body">
<div class="row">
    <div class="col-md-12">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2 mb-3">
            <div class="col-auto">
                {{ Form::text('search_query', request('search_query'), ['class' => 'form-control', 'placeholder' => __('transaction.search_text'), 'style' => 'width:300px']) }}
            </div>
            <div class="col-auto">
                {{ Form::text('start_date', $startDate, ['class' => 'form-control date-select', 'style' => 'width:100px', 'placeholder' => __('time.start_date')]) }}
            </div>
            <div class="col-auto">
                {{ Form::text('end_date', $endDate, ['class' => 'form-control date-select', 'style' => 'width:100px', 'placeholder' => __('time.end_date')]) }}
            </div>
            <div class="col-auto">
                {{ Form::select('category_id', $categories, request('category_id'), ['placeholder' => __('category.all'), 'class' => 'form-control', 'style' => 'width:200px']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => '-- '.__('transaction.origin_destination').' --', 'class' => 'form-control']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('app.search'), ['class' => 'btn btn-primary']) }}
                {{ link_to_route('transaction_search.index', __('app.reset'), [], ['class' => 'btn']) }}
            </div>
        </div>
        {{ Form::close() }}

        @if ($searchQuery)
            <div class="card table-responsive">
                @desktop
                <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">{{ __('app.table_no') }}</th>
                            <th class="col-md-2">{{ __('app.date') }}</th>
                            <th class="col-md-7">{{ __('transaction.description') }}</th>
                            <th class="text-end col-md-2">{{ __('transaction.amount') }}</th>
                            <th class="text-center">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $transaction)
                        <tr>
                            <td class="text-center">{{ 1 + $key }}</td>
                            <td>{{ $transaction->date }} ({{ $transaction->day_name }})</td>
                            <td>
                                <div class="float-end">
                                    @if ($transaction->partner)
                                        @php
                                            $partnerRoute = route('partners.show', [
                                                $transaction->partner_id,
                                                'start_date' => $startDate,
                                                'end_date' => $endDate,
                                            ]);
                                        @endphp
                                        <a class="badge bg-info text-info-fg" href="{{ $partnerRoute }}">{{ $transaction->partner->name }}</a>
                                    @endif
                                    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple text-purple-fg' : 'bg-secondary text-secondary-fg'}}">
                                        {{ $transaction->bankAccount->name }}
                                    </span>
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
                                <div style="max-width: 600px" class="me-3">{!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}</div>
                            </td>
                            <td class="text-end">{{ $transaction->amount_string }}</td>
                            <td class="text-center text-nowrap">
                                {{ link_to_route('transactions.show', __('app.show'), [
                                    $transaction,
                                    'query' => $searchQuery,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                    'reference_page' => 'transaction_search',
                                ], ['class' => 'btn btn-sm']) }}
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
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@push('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endpush

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
