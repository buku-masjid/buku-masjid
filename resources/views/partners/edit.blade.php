@extends('layouts.app')

@section('title', __('partner.edit', ['type' => $partner->type]))

@section('content')

@if (request('action') == 'delete' && $partner)
<div class="row justify-content-center">
    <div class="col-md-8">
        @can('delete', $partner)
            <div class="card">
                <div class="card-header"><h5 class="card-title">{{ __('partner.delete', ['type' => $partner->type]) }}</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('partner.name') }}</label>
                            <p>{{ $partner->name }}</p>
                            <label class="control-label text-primary">{{ __('partner.phone') }}</label>
                            <p>{{ $partner->phone }}</p>
                            <label class="control-label text-primary">{{ __('partner.work') }}</label>
                            <p>{{ $partner->work ?: '-' }}</p>
                            <label class="control-label text-primary">{{ __('partner.type') }} / {{ __('partner.level') }}</label>
                            <p>{{ $partner->type ?: '-' }} / {{ $partner->level ?: '-' }}</p>
                            <label class="control-label text-primary">{{ __('address.address') }}</label>
                            <p>{{ $partner->address ?: '-' }}</p>
                            {!! $errors->first('partner_id', '<span class="form-error small">:message</span>') !!}
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('partner.transaction_total') }}</label>
                            <p>{{ format_number($partner->transactions_sum_amount ?: 0) }}</p>
                        </div>
                    </div>
                </div>
                <hr style="margin:0">
                @if ($partner->transactions_sum_amount)
                    <div class="card-body bg-warning">
                        <div class="row">
                            <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                            <div class="col-11">{!! __('partner.undeleteable', ['type' => $partner->type]) !!}</div>
                        </div>
                    </div>
                @else
                    <div class="card-body bg-warning">
                        <div class="row">
                            <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                            <div class="col-11">{!! __('partner.delete_confirm', ['type' => $partner->type]) !!}</div>
                        </div>
                    </div>
                @endif
                <div class="card-footer">
                    @if (!$partner->transactions_sum_amount)
                        {!! FormField::delete(
                            ['route' => ['partners.destroy', $partner], 'onsubmit' => __('app.delete_confirm')],
                            __('app.delete_confirm_button'),
                            ['class' => 'btn btn-danger'],
                            ['partner_id' => $partner->id]
                        ) !!}
                    @endif
                    {{ link_to_route('partners.edit', __('app.cancel'), [$partner], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        @endcan

    </div>
</div>
@else
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title">{{ __('partner.edit', ['type' => $partner->type]) }}</h5></div>
            {!! Form::model($partner, ['route' => ['partners.update', $partner], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                        <div class="row">
                            <div class="col-md-7">
                                {!! FormField::select('gender_code', $genders, [
                                    'placeholder' => false,
                                    'label' => __('app.gender'),
                                ]) !!}
                                {!! FormField::text('phone', ['label' => __('partner.phone'), 'type' => 'number']) !!}
                            </div>
                            <div class="col-md-5">
                                {!! FormField::checkboxes('type_code', $partnerTypes, [
                                    'value' => old('type_code', $partner->type_code),
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
                        {!! FormField::select('religion_id', __('partner.religions'), ['label' => __('partner.religion'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('work_type_id', __('partner.work_types'), ['label' => __('partner.work'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::text('work', ['label' => __('partner.work_detail')]) !!}
                        {!! FormField::select('marital_status_id', __('partner.marital_statuses'), ['label' => __('partner.marital_status'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('financial_status_id', __('partner.financial_statuses'), ['label' => __('partner.financial_status'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::select('activity_status_id', __('partner.activity_statuses'), ['label' => __('partner.activity_status'), 'placeholder' => __('app.unknown')]) !!}
                        {!! FormField::radios('is_active', [__('app.inactive'), __('app.active')], ['label' => __('app.status')]) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit(__('partner.update', ['type' => $partner->type]), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('partners.show', __('app.cancel'), $partner, ['class' => 'btn btn-secondary']) }}
                @can('delete', $partner)
                    {!! link_to_route(
                        'partners.edit',
                        __('app.delete'),
                        [$partner->id, 'action' => 'delete'],
                        ['id' => 'del-partner-'.$partner->id, 'class' => 'btn btn-danger float-right']
                    ) !!}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
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
