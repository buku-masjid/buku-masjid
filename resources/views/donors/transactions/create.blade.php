@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        @section('title', __('donor.add_donation'))
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('donor.add_donation') }}</h5>
            </div>
            {!! Form::open(['route' => 'donor_transactions.store', 'autocomplete' => 'off']) !!}
            <div class="card-body">
                @unless (request('partner_id') && isset($partners[request('partner_id')]))
                    <div class="btn-group btn-block mb-4">
                        <a href="{{ route('donor_transactions.create') }}" class="btn {{ in_array(request('action'), [null]) ? 'btn-primary' : 'btn-secondary' }}">{{ __('donor.search') }}</a>
                        <a href="{{ route('donor_transactions.create', ['action' => 'new_donor']) }}" class="btn {{ in_array(request('action'), ['new_donor']) ? 'btn-primary' : 'btn-secondary' }}">{{ __('donor.new') }}</a>
                    </div>
                @endunless
                @if (request('action') == 'new_donor')
                    {!! FormField::text('partner_name', ['required' => true, 'label' => __('donor.name')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('partner_phone', ['label' => __('donor.phone')]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::select('partner_gender_code', $genders, [
                                'value' => old('partner_gender_code', request('partner_gender_code')),
                                'required' => true,
                                'placeholder' => false,
                                'label' => __('app.gender'),
                            ]) !!}
                        </div>
                    </div>
                    {{ Form::hidden('partner_id', '') }}
                @elseif (request('partner_id') && isset($partners[request('partner_id')]))
                    {!! FormField::textDisplay('partner_name', $partners[request('partner_id')], ['required' => true, 'label' => __('donor.name')]) !!}
                    {{ Form::hidden('partner_id', request('partner_id')) }}
                @else
                    {!! FormField::select('partner_id', $partners, [
                        'label' => __('donor.select'),
                        'required' => true,
                        'placeholder' => __('donor.name'),
                        'value' => old('partner_id', request('partner_id')),
                    ]) !!}
                @endif
                <div class="row">
                    <div class="col-md-4">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                    <div class="col-md-8">
                        {!! FormField::select('book_id', $books, ['required' => true, 'label' => __('book.book'), 'placeholder' => __('book.select')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('amount', [
                            'required' => true,
                            'label' => __('transaction.amount'),
                            'addon' => ['before' => config('money.currency_code')],
                            'step' => number_step(),
                        ]) !!}
                    </div>
                    <div class="col-md-6">{!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.destination'), 'placeholder' => __('transaction.cash')]) !!}</div>
                </div>
                {!! FormField::textarea('notes', ['label' => __('donor.notes'), 'placeholder' => __('donor.notes_placeholder')]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('donor.add_donation'), ['class' => 'btn btn-success']) !!}
                {{ Form::hidden('reference_page', request('reference_page')) }}
                @if (request('partner_id') && isset($partners[request('partner_id')]))
                    {{-- expr --}}
                    {{ link_to_route('donors.show', __('app.cancel'), [request('partner_id')], ['class' => 'btn btn-secondary']) }}
                @else
                    {{ link_to_route('donors.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                @endif
            </div>
            {{ Form::close() }}
        </div>
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
