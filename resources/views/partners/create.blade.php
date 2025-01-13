@extends('layouts.app')

@section('title', __('partner.create'))

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('partner.create') }}</h5>
            </div>
            {!! Form::open(['route' => 'partners.store']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                        <div class="row">
                            <div class="col-md-7">
                                {!! FormField::select('gender_code', $genders, [
                                    'value' => old('gender_code', request('gender_code')),
                                    'placeholder' => false,
                                    'label' => __('app.gender'),
                                ]) !!}
                                {!! FormField::text('phone', ['label' => __('partner.phone'), 'type' => 'number']) !!}
                            </div>
                            <div class="col-md-5">
                                {!! FormField::checkboxes('type_code', $partnerTypes, [
                                    'value' => old('type_code', request('type_code')),
                                    'placeholder' => false,
                                    'label' => __('partner.type'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">{!! FormField::text('pob', ['label' => __('partner.pob')]) !!}</div>
                            <div class="col-md-4">{!! FormField::text('dob', ['label' => __('partner.dob'), 'class' => 'date-select']) !!}</div>
                        </div>
                        {!! FormField::textarea('address', ['label' => __('address.address')]) !!}
                        <div class="row">
                            <div class="col-6">{!! FormField::text('rt', ['label' => __('address.rt')]) !!}</div>
                            <div class="col-6">{!! FormField::text('rw', ['label' => __('address.rw')]) !!}</div>
                        </div>
                        {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                    </div>
                    <div class="col-md-6">
                        {{ Form::hidden('level_code') }}
                        {!! FormField::select('religion_id', __('partner.religions'), ['label' => __('partner.religion'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('work_type_id', __('partner.work_types'), ['label' => __('partner.work'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::text('work', ['label' => __('partner.work_detail')]) !!}
                        {!! FormField::select('marital_status_id', __('partner.marital_statuses'), ['label' => __('partner.marital_status'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('financial_status_id', __('partner.financial_statuses'), ['label' => __('partner.financial_status'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('activity_status_id', __('partner.activity_statuses'), ['label' => __('partner.activity_status'), 'placeholder' => __('app.unknown')]) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit(__('partner.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
            </div>
            {{ Form::close() }}
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
