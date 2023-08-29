<div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4">&#128214; {{ __('lecturing_schedule.lecturing') }}</td>
                <td><strong>{{ $lecturingSchedule->day_name }}, {{ $lecturingSchedule->time_text }}</strong></td>
            </tr>
            <tr><td>&#128467;&#65039; {{ __('time.date') }}</td><td>{{ $lecturingSchedule->full_date }}</td></tr>
            <tr><td>&#9200; {{ __('lecturing_schedule.time') }}</td><td>{{ $lecturingSchedule->time }}</td></tr>
            <tr><td>&#128115;&#127997; {{ __('lecturing_schedule.lecturer_name') }}</td><td>{{ $lecturingSchedule->lecturer_name }}</td></tr>
            @if ($lecturingSchedule->book_title)
                <tr><td>&#128216; {{ __('lecturing_schedule.book') }}</td><td>{{ $lecturingSchedule->book_title }}</td></tr>
            @endif
            @if ($lecturingSchedule->book_writer)
                <tr><td>&#9997;&#65039; {{ __('lecturing_schedule.written_by') }}</td><td>{{ $lecturingSchedule->book_writer }}</td></tr>
            @endif
            @if ($lecturingSchedule->book_link)
                <tr><td>&#11015;&#65039; {{ __('lecturing_schedule.book_link') }}</td><td>{{ $lecturingSchedule->book_link }}</td></tr>
            @endif
            @if ($lecturingSchedule->video_link)
                <tr><td>&#128250; {{ __('lecturing_schedule.video_link') }}</td><td>{{ $lecturingSchedule->video_link }}</td></tr>
            @endif
            @if ($lecturingSchedule->audio_link)
                <tr><td>&#128266; {{ __('lecturing_schedule.audio_link') }}</td><td>{{ $lecturingSchedule->audio_link }}</td></tr>
            @endif
            @if ($lecturingSchedule->title)
                <tr><td>&#128172; {{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
            @endif
            @if ($lecturingSchedule->description)
                <tr><td>&#128466;&#65039; {{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
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
