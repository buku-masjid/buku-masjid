@extends('layouts.app')

@section('title', __('transaction.list'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        @can('create', new App\Transaction)
        @if (request('action') == 'add-income')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('transaction.add_income') }}</h5>
                    <div class="card-options btn-group">
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-income'])) }}" class="btn btn-gray btn-sm">{{ __('transaction.income') }}</a>
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-spending'])) }}" class="btn btn-secondary btn-sm">{{ __('transaction.spending') }}</a>
                    </div>
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off']) !!}
                {{ Form::hidden('in_out', 1) }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('money.currency_code'), 'step' => number_step()]) !!}</div>
                    </div>
                    {!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.destination'), 'placeholder' => __('transaction.cash')]) !!}
                </div>
                <div class="card-footer">
                    {!! Form::submit(__('transaction.add_income'), ['class' => 'btn btn-success']) !!}
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {{ link_to_route('transactions.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        @endif
        @if (request('action') == 'add-spending')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('transaction.add_spending') }}</h5>
                    <div class="card-options btn-group">
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-income'])) }}" class="btn btn-secondary btn-sm">{{ __('transaction.income') }}</a>
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-spending'])) }}" class="btn btn-gray btn-sm">{{ __('transaction.spending') }}</a>
                    </div>
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off']) !!}
                {{ Form::hidden('in_out', 0) }}
                <div class="card-body">
                    {!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.origin'), 'placeholder' => __('transaction.cash')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('money.currency_code'), 'step' => number_step()]) !!}</div>
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit(__('transaction.add_spending'), ['class' => 'btn btn-danger']) !!}
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {{ link_to_route('transactions.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        @endif
        @endcan
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
