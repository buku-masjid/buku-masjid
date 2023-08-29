<div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4">{!! config('lecturing.emoji.date') !!} {{ __('time.day_name') }}/{{ __('time.date') }}</td>
                <td><strong>{{ $lecturingSchedule->day_name }}</strong>, {{ $lecturingSchedule->full_date }}</td>
            </tr>
            <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing_schedule.time') }}</td><td>{{ $lecturingSchedule->start_time }}</td></tr>
            <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing_schedule.friday_lecturer_name') }}</td><td>{{ $lecturingSchedule->lecturer_name }}</td></tr>
            @if ($lecturingSchedule->video_link)
                <tr><td>{!! config('lecturing.emoji.video_link') !!} {{ __('lecturing_schedule.video_link') }}</td><td>{{ $lecturingSchedule->video_link }}</td></tr>
            @endif
            @if ($lecturingSchedule->audio_link)
                <tr><td>{!! config('lecturing.emoji.audio_link') !!} {{ __('lecturing_schedule.audio_link') }}</td><td>{{ $lecturingSchedule->audio_link }}</td></tr>
            @endif
            @if ($lecturingSchedule->title)
                <tr><td>{!! config('lecturing.emoji.title') !!} {{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
            @endif
            @if ($lecturingSchedule->description)
                <tr><td>{!! config('lecturing.emoji.description') !!} {{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
            @endif
        </tbody>
        <tfoot class="table">
            <tr>
                <td colspan="2" class="text-center">
                    @can('view', $lecturingSchedule)
                        {{ link_to_route(
                            'lecturing_schedules.show',
                            __('app.show'),
                            [$lecturingSchedule],
                            ['id' => 'show-lecturing_schedule-' . $lecturingSchedule->id]
                        ) }}
                    @endcan
                </td>
            </tr>
        </tfoot>
    </table>
</div>
