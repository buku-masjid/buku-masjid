@extends('layouts.app')

@section('title', __('lecturing_schedule.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $lecturingSchedule->audience }}</span>
                {{ __('lecturing_schedule.detail') }}
            </div>
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="col-4">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing_schedule.lecturing') }}</td>
                        <td><strong>{{ $lecturingSchedule->day_name }}, {{ $lecturingSchedule->time_text }}</strong></td>
                    </tr>
                    <tr><td>{!! config('lecturing.emoji.date') !!} {{ __('time.date') }}</td><td>{{ $lecturingSchedule->full_date }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing_schedule.time') }}</td><td>{{ $lecturingSchedule->time }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing_schedule.lecturer_name') }}</td><td>{{ $lecturingSchedule->lecturer_name }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.book') !!} {{ __('lecturing_schedule.book') }}</td><td>{{ $lecturingSchedule->book_title }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.written_by') !!} {{ __('lecturing_schedule.written_by') }}</td><td>{{ $lecturingSchedule->book_writer }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.book_link') !!} {{ __('lecturing_schedule.book_link') }}</td><td>{{ $lecturingSchedule->book_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.video_link') !!} {{ __('lecturing_schedule.video_link') }}</td><td>{{ $lecturingSchedule->video_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.audio_link') !!} {{ __('lecturing_schedule.audio_link') }}</td><td>{{ $lecturingSchedule->audio_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.title') !!} {{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.description') !!} {{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
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
