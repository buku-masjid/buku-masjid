@extends('layouts.app')

@section('title', __('lecturing_schedule.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $lecturingSchedule->audience }}</span>
                {{ __('lecturing_schedule.detail') }}
            </div>
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td>{{ __('lecturing_schedule.date') }}</td><td>{{ $lecturingSchedule->date }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.start_time') }}</td><td>{{ $lecturingSchedule->start_time }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.end_time') }}</td><td>{{ $lecturingSchedule->end_time }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.time_text') }}</td><td>{{ $lecturingSchedule->time_text }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.lecturer') }}</td><td>{{ $lecturingSchedule->lecturer }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.book_title') }}</td><td>{{ $lecturingSchedule->book_title }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.book_writer') }}</td><td>{{ $lecturingSchedule->book_writer }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.book_link') }}</td><td>{{ $lecturingSchedule->book_link }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.video_link') }}</td><td>{{ $lecturingSchedule->video_link }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.audio_link') }}</td><td>{{ $lecturingSchedule->audio_link }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
                    <tr><td>{{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $lecturingSchedule)
                    {{ link_to_route('lecturing_schedules.edit', __('lecturing_schedule.edit'), [$lecturingSchedule], ['class' => 'btn btn-warning', 'id' => 'edit-lecturing_schedule-'.$lecturingSchedule->id]) }}
                @endcan
                {{ link_to_route('lecturing_schedules.index', __('lecturing_schedule.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
