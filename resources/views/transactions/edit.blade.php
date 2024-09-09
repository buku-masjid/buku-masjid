@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        @if (is_null(request('action')))
            @can('update', $transaction)
                @section('title', __('transaction.edit').' #'.$transaction->id)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('transaction.edit') }}</h5>
                    </div>
                    {!! Form::model($transaction, ['route' => ['transactions.update', $transaction], 'method' => 'patch', 'autocomplete' => 'off']) !!}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'class' => 'date-select']) !!}</div>
                            <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                        </div>
                        {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                        <div class="row">
                            <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('money.currency_code'), 'step' => number_step()]) !!}</div>
                            <div class="col-md-6">{!! FormField::radios('in_out', [__('transaction.spending'), __('transaction.income')], ['required' => true, 'label' => __('transaction.transaction'), 'list_style' => 'unstyled']) !!}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">{!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.origin_destination'), 'placeholder' => __('transaction.cash')]) !!}</div>
                            <div class="col-md-6">{!! FormField::select('partner_id', $partners, ['label' => __('partner.partner'), 'placeholder' => __('partner.partner')]) !!}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {!! Form::submit(__('transaction.update'), ['class' => 'btn btn-success']) !!}
                        {{ Form::hidden('query', request('query')) }}
                        {{ Form::hidden('queried_category_id', request('category_id')) }}
                        {{ Form::hidden('queried_partner_id', request('partner_id')) }}
                        {{ Form::hidden('reference_page', request('reference_page')) }}
                        {{ Form::hidden('start_date', request('start_date')) }}
                        {{ Form::hidden('end_date', request('end_date')) }}
                        {{ link_to_route('transactions.show', __('app.cancel'), [$transaction, 'month' => $transaction->month, 'year' => $transaction->year], ['class' => 'btn btn-secondary']) }}
                        @can('delete', $transaction)
                            {!! link_to_route(
                                'transactions.edit',
                                __('app.delete'),
                                [$transaction, 'action' => 'delete'] + Request::only('reference_page', 'month', 'year', 'start_date', 'end_date', 'category_id', 'partner_id'),
                                ['id' => 'del-transaction-'.$transaction->id, 'class' => 'btn btn-danger float-right']
                            ) !!}
                        @endcan
                    </div>
                    {{ Form::close() }}
                </div>
            @endcan
        @endif

        @if (request('action') == 'delete')
            @can('delete', $transaction)
                @section('title', __('transaction.delete').' #'.$transaction->id)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('app.delete') }} {{ $transaction->type }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">{{ __('app.date') }}</label>
                                <p>{{ $transaction->date }}</p>
                                <label class="control-label">{{ __('transaction.amount') }}</label>
                                <p>{{ $transaction->amount_string }}</p>
                                <label class="control-label">{{ __('transaction.description') }}</label>
                                <p>{{ $transaction->description }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">{{ __('category.category') }}</label>
                                <p>{{ optional($transaction->category)->name }}</p>
                                <label class="control-label">{{ __('partner.partner') }}</label>
                                <p>{{ optional($transaction->partner)->name }}</p>
                                <label class="control-label">{{ __('transaction.origin_destination') }}</label>
                                <p>{{ $transaction->bankAccount->name }}</p>
                            </div>
                        </div>
                        {!! $errors->first('transaction_id', '<span class="form-error small">:message</span>') !!}
                    </div>
                    <hr style="margin:0">
                    <div class="card-body">{{ __('app.delete_confirm') }}</div>
                    <div class="card-footer">
                        {!! FormField::delete(
                            ['route' => ['transactions.destroy', $transaction], 'class' => ''],
                            __('app.delete_confirm_button'),
                            ['class'=>'btn btn-danger'],
                            [
                                'transaction_id' => $transaction->id,
                                'category_id' => $transaction->category_id,
                                'partner_id' => $transaction->partner_id,
                            ] + request(['reference_page', 'end_date', 'start_date'])
                        ) !!}
                        {{ link_to_route('transactions.edit', __('app.cancel'), [$transaction, 'month' => $transaction->month, 'year' => $transaction->year], ['class' => 'btn btn-secondary']) }}
                    </div>
                </div>
            @endcan
        @endif
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
    {{ Html::style(url('css/plugins/select2.min.css')) }}
    {{ Html::style(url('css/plugins/select2-bootstrap.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
    {{ Html::script(url('js/plugins/select2.min.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
    $('#partner_id').select2({theme: "bootstrap"});
})();
</script>
@endpush
