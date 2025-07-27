@extends('layouts.app')

@section('title', __('donor.edit'))

@section('content')

@if (request('action') == 'delete' && $partner)
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
        @can('delete', $partner)
            <div class="card">
                <div class="card-header"><h5 class="card-title">{{ __('donor.delete') }}</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('donor.name') }}</label>
                            <p>{{ $partner->name }}</p>
                            <label class="control-label text-primary">{{ __('donor.phone') }}</label>
                            <p>{{ $partner->phone }}</p>
                            <label class="control-label text-primary">{{ __('partner.work') }}</label>
                            <p>{{ $partner->work ?: '-' }}</p>
                            <label class="control-label text-primary">{{ __('partner.level') }}</label>
                            <p>{{ $partner->level ?: '-' }}</p>
                            <label class="control-label text-primary">{{ __('partner.address') }}</label>
                            <p>{{ $partner->address ?: '-' }}</p>
                            {!! $errors->first('partner_id', '<span class="form-error small">:message</span>') !!}
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('donor.donation_total') }}</label>
                            <p>{{ format_number($partner->transactions_sum_amount ?: 0) }}</p>
                        </div>
                    </div>
                </div>
                <hr style="margin:0">
                @if ($partner->transactions_sum_amount)
                    <div class="card-body bg-warning">
                        <div class="row">
                            <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                            <div class="col-11">{!! __('donor.undeleteable') !!}</div>
                        </div>
                    </div>
                @else
                    <div class="card-body bg-warning">
                        <div class="row">
                            <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                            <div class="col-11">{!! __('donor.delete_confirm') !!}</div>
                        </div>
                    </div>
                @endif
                <div class="card-footer">
                    @if (!$partner->transactions_sum_amount)
                        {!! FormField::delete(
                            ['route' => ['donors.destroy', $partner], 'onsubmit' => __('app.delete_confirm')],
                            __('app.delete_confirm_button'),
                            ['class' => 'btn btn-danger'],
                            ['partner_id' => $partner->id]
                        ) !!}
                    @endif
                    {{ link_to_route('donors.edit', __('app.cancel'), [$partner], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        @endcan

    </div>
</div>
@else
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="card">
            <div class="card-header"><h5 class="card-title">{{ __('donor.edit') }}</h5></div>
            {!! Form::model($partner, ['route' => ['donors.update', $partner], 'method' => 'patch']) !!}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('donor.name')]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::select('gender_code', $genders, [
                            'value' => old('gender_code', $partner->gender_code),
                            'required' => true,
                            'placeholder' => false,
                            'label' => __('app.gender'),
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::select('level_code', $partnerLevels, [
                            'value' => old('level_code', $selectedPartnerLevel),
                            'label' => __('partner.level'),
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">{!! FormField::text('phone', ['label' => __('donor.phone')]) !!}</div>
                    <div class="col-md-7">{!! FormField::text('work', ['label' => __('partner.work')]) !!}</div>
                </div>
                {!! FormField::textarea('address', ['label' => __('partner.address')]) !!}
                {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                {!! FormField::radios('is_active', [__('app.inactive'), __('app.active')], ['label' => __('app.status')]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('donor.update'), ['class' => 'btn btn-warning text-dark']) !!}
                {{ link_to_route('donors.show', __('app.cancel'), $partner, ['class' => 'btn btn-secondary']) }}
                @can('delete', $partner)
                    {!! link_to_route(
                        'donors.edit',
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
