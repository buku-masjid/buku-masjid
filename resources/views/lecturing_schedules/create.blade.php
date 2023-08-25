@extends('layouts.app')

@section('title', __('lecturing_schedule.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.create') }}</div>
            {{ Form::open(['route' => 'lecturing_schedules.store']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">{!! FormField::text('date', ['required' => true, 'label' => __('lecturing_schedule.date'), 'value' => old('date', date('Y-m-d'))]) !!}</div>
                    <div class="col-md-8">
                        {!! FormField::radios('audience_code', $audienceCodes, [
                            'required' => true,
                            'label' => __('lecturing_schedule.audience'),
                            'list_style' => 'unstyled',
                            'value' => old('audience_code', App\Models\LecturingSchedule::AUDIENCE_PUBLIC),
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-3">{!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing_schedule.start_time')]) !!}</div>
                    <div class="col-6 col-md-3">{!! FormField::text('end_time', ['label' => __('lecturing_schedule.end_time')]) !!}</div>
                    <div class="col-12 col-md-6">{!! FormField::text('time_text', ['label' => __('lecturing_schedule.time_text')]) !!}</div>
                </div>
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
