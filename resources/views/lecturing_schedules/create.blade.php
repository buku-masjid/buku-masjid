@extends('layouts.app')

@section('title', __('lecturing_schedule.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.create') }}</div>
            {{ Form::open(['route' => 'lecturing_schedules.store']) }}
            <div class="card-body">
                {!! FormField::text('title', ['required' => true, 'label' => __('lecturing_schedule.title')]) !!}
                {!! FormField::textarea('description', ['label' => __('lecturing_schedule.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('lecturing_schedules.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
