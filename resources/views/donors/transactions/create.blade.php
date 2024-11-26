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
                <div class="btn-group btn-block mb-4">
                    <a href="{{ route('donor_transactions.create') }}" class="btn {{ in_array(request('action'), [null]) ? 'btn-primary' : 'btn-secondary' }}">{{ __('donor.search') }}</a>
                    <a href="{{ route('donor_transactions.create', ['action' => 'new_donor']) }}" class="btn {{ in_array(request('action'), ['new_donor']) ? 'btn-primary' : 'btn-secondary' }}">{{ __('donor.new_donor') }}</a>
                </div>
                @if (request('action') == 'new_donor')
                    {!! FormField::text('partner_name', ['label' => __('donor.name')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('partner_phone', ['label' => __('donor.phone')]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::select('partner_gender_code', $genders, [
                                'value' => old('partner_gender_code', request('partner_gender_code')),
                                'placeholder' => false,
                                'label' => __('app.gender'),
                            ]) !!}
                        </div>
                    </div>
                    {{ Form::hidden('partner_id', '') }}
                @else
                    {!! FormField::select('partner_id', $partners, [
                        'label' => __('donor.search'),
                        'placeholder' => __('donor.search'),
                    ]) !!}
                @endif
                <div class="row">
                    <div class="col-md-4">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                    <div class="col-md-8">
                        {!! FormField::select('book_id', $books, ['label' => __('book.book'), 'placeholder' => __('book.select')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('money.currency_code'), 'step' => number_step()]) !!}</div>
                    <div class="col-md-6">{!! FormField::select('bank_account_id', $bankAccounts, ['label' => __('transaction.destination'), 'placeholder' => __('transaction.cash')]) !!}</div>
                </div>
                {!! FormField::textarea('notes', ['label' => __('donor.notes'), 'placeholder' => __('donor.notes_placeholder')]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('donor.add_donation'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('transactions.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
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
