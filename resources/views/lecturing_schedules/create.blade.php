@extends('layouts.app')

@section('title', __('lecturing_schedule.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.create') }}</div>
            {{ Form::open(['route' => 'lecturing_schedules.store']) }}
            <div class="card-body">
                {!! FormField::text('audience_code', ['required' => true, 'label' => __('lecturing_schedule.title')]) !!}
                {!! FormField::text('date', ['required' => true, 'label' => __('lecturing_schedule.date')]) !!}
                {!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing_schedule.start_time')]) !!}
                {!! FormField::text('end_time', ['label' => __('lecturing_schedule.end_time')]) !!}
                {!! FormField::text('time_text', ['required' => true, 'label' => __('lecturing_schedule.time_text')]) !!}
                {!! FormField::text('lecturer', ['required' => true, 'label' => __('lecturing_schedule.lecturer')]) !!}
                {!! FormField::text('book_title', ['label' => __('lecturing_schedule.book_title')]) !!}
                {!! FormField::text('book_writer', ['label' => __('lecturing_schedule.book_writer')]) !!}
                {!! FormField::text('book_link', ['label' => __('lecturing_schedule.book_link')]) !!}
                {!! FormField::text('video_link', ['label' => __('lecturing_schedule.video_link')]) !!}
                {!! FormField::text('audio_link', ['label' => __('lecturing_schedule.audio_link')]) !!}
                {!! FormField::text('title', ['label' => __('lecturing_schedule.title')]) !!}
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
