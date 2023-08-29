@extends('layouts.app')

@section('title', __('lecturing_schedule.detail_for_friday'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $lecturingSchedule->audience }}</span>
                {{ __('lecturing_schedule.detail_for_friday') }}
            </div>
            <table class="table card-table table-sm">
                <tbody>
                    <tr>
                        <td>{!! config('lecturing.emoji.date') !!} {{ __('time.day_name') }}/{{ __('time.date') }}</td>
                        <td>{{ $lecturingSchedule->day_name }}, {{ $lecturingSchedule->full_date }}</td>
                    </tr>
                    <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing_schedule.time') }}</td><td>{{ $lecturingSchedule->start_time }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing_schedule.friday_lecturer_name') }}</td><td>{{ $lecturingSchedule->lecturer_name }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.video_link') !!} {{ __('lecturing_schedule.video_link') }}</td><td>{{ $lecturingSchedule->video_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.audio_link') !!} {{ __('lecturing_schedule.audio_link') }}</td><td>{{ $lecturingSchedule->audio_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.title') !!} {{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.description') !!} {{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $lecturingSchedule)
                    {{ link_to_route('friday_lecturing_schedules.edit', __('lecturing_schedule.edit_for_friday'), [$lecturingSchedule], ['class' => 'btn btn-warning', 'id' => 'edit-lecturing_schedule-'.$lecturingSchedule->id]) }}
                @endcan
                {{ link_to_route('lecturing_schedules.index', __('lecturing_schedule.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
