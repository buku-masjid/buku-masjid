@extends('layouts.app')

@section('title', __('donor.create'))

@section('content')

<div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('donor.create') }}</h5>
            </div>
            {!! Form::open(['route' => 'donors.store']) !!}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('donor.name')]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::select('gender_code', $genders, [
                            'value' => old('gender_code', request('gender_code')),
                            'required' => true,
                            'placeholder' => false,
                            'label' => __('app.gender'),
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        @if ($partnerLevels)
                            {!! FormField::select('level_code', $partnerLevels, [
                                'value' => old('level_code', request('level_code')),
                                'placeholder' => false,
                                'label' => __('partner.level'),
                            ]) !!}
                        @else
                            {{ Form::hidden('level_code') }}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">{!! FormField::text('phone', ['label' => __('donor.phone'), 'type' => 'number']) !!}</div>
                    <div class="col-md-7">{!! FormField::text('work', ['label' => __('partner.work')]) !!}</div>
                </div>
                {!! FormField::textarea('address', ['label' => __('partner.address')]) !!}
                {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('donor.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('donors.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
