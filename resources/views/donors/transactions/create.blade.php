@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        @can('create', new App\Transaction)
            @section('title', __('donor.add_donation'))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('donor.add_donation') }}</h5>
                </div>
                {!! Form::open(['route' => 'donor_transactions.store', 'autocomplete' => 'off']) !!}
                <div class="card-body">
                    {!! FormField::select('partner_id', $partners, [
                        'label' => __('partner.partner_type_donor'),
                        'placeholder' => __('donor.select'),
                    ]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">
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
