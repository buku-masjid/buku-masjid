@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        @can('create', new App\Transaction)
        @if (request('action') == 'add-income')
            @section('title', __('transaction.income'))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('transaction.add_income') }}</h5>
                    <div class="card-options btn-group">
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-income'])) }}" class="btn btn-gray btn-sm">{{ __('transaction.income') }}</a>
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-spending'])) }}" class="btn btn-secondary btn-sm">{{ __('transaction.spending') }}</a>
                    </div>
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off', 'files' => true]) !!}
                {{ Form::hidden('in_out', 1) }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('date', [
                                'required' => true,
                                'label' => __('app.date'),
                                'value' => old('date', $selectedDate),
                                'class' => 'date-select'
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::select('category_id', $categories, [
                                'label' => __('category.category'),
                                'placeholder' => __('category.uncategorized'),
                                'info' => ['text' => __('transaction.category_help_text', ['link' => $categorySettingLink])],
                            ]) !!}
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('amount', [
                                'required' => true,
                                'label' => __('transaction.amount'),
                                'addon' => ['before' => config('money.currency_code')],
                                'step' => number_step(),
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            @if ($partnerTypeCodes)
                                {!! FormField::select('partner_id', $partners, [
                                    'label' => $partnerSelectionLabel,
                                    'placeholder' => $partnerDefaultValue,
                                    'info' => ['text' => __('transaction.partner_help_text', ['partner' => $partnerSelectionLabel, 'link' => $partnerSettingLink])],
                                ]) !!}
                            @else
                                {{ Form::hidden('partner_id') }}
                            @endif
                        </div>
                    </div>
                    {!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.destination'), 'placeholder' => __('transaction.cash')]) !!}
                    <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                        <label for="files" class="form-label fw-bold">{{ __('transaction.upload_files') }}</label>
                        {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control '.($errors->has('files.*') ? 'is-invalid' : ''), 'accept' => 'image/*']) }}
                        @if ($errors->has('files.*'))
                            @foreach ($errors->get('files.*') as $key => $errorMessages)
                                {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            @endforeach
                        @endif
                    </div>
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
            @section('title', __('transaction.spending'))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('transaction.add_spending') }}</h5>
                    <div class="card-options btn-group">
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-income'])) }}" class="btn btn-secondary btn-sm">{{ __('transaction.income') }}</a>
                        <a href="{{ route('transactions.create', array_merge(request()->only(['action', 'year','month']), ['action' => 'add-spending'])) }}" class="btn btn-gray btn-sm">{{ __('transaction.spending') }}</a>
                    </div>
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off', 'files' => true]) !!}
                {{ Form::hidden('in_out', 0) }}
                <div class="card-body">
                    {!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.origin'), 'placeholder' => __('transaction.cash')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('date', [
                                'required' => true,
                                'label' => __('app.date'),
                                'value' => old('date', $selectedDate),
                                'class' => 'date-select'
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::select('category_id', $categories, [
                                'label' => __('category.category'),
                                'placeholder' => __('category.uncategorized'),
                                'info' => ['text' => __('transaction.category_help_text', ['link' => $categorySettingLink])],
                            ]) !!}
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('amount', [
                                'required' => true,
                                'label' => __('transaction.amount'),
                                'addon' => ['before' => config('money.currency_code')],
                                'step' => number_step(),
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            @if ($partnerTypeCodes)
                                {!! FormField::select('partner_id', $partners, [
                                    'label' => $partnerSelectionLabel,
                                    'placeholder' => $partnerDefaultValue,
                                    'info' => ['text' => __('transaction.partner_help_text', ['partner' => $partnerSelectionLabel, 'link' => $partnerSettingLink])],
                                ]) !!}
                            @else
                                {{ Form::hidden('partner_id') }}
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                        <label for="files" class="form-label fw-bold">{{ __('transaction.upload_files') }}</label>
                        {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control '.($errors->has('files.*') ? 'is-invalid' : ''), 'accept' => 'image/*']) }}
                        @if ($errors->has('files.*'))
                            @foreach ($errors->get('files.*') as $key => $errorMessages)
                                {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            @endforeach
                        @endif
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
    {{ Html::style(url('css/plugins/select2.min.css')) }}
    {{ Html::style(url('css/plugins/select2-bootstrap.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
    {{ Html::script(url('js/plugins/select2.min.js')) }}
    {{ Html::script(url('js/plugins/number-format.js')) }}
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
    initNumberFormatter('#amount', {
        thousandSeparator: '{{ config('money.thousands_separator') }}',
        decimalSeparator: '{{ config('money.decimal_separator') }}'
    });
})();
</script>
@endpush
