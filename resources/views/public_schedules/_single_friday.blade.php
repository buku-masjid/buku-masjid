<div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4 col-sm-3">&#128467;&#65039; {{ __('time.day_name') }}/{{ __('time.date') }}</td>
                <td>{{ $lecturingSchedule->day_date }}</td>
            </tr>
            <tr><td>&#9200; {{ __('lecturing_schedule.time') }}</td><td>{{ $lecturingSchedule->start_time }}</td></tr>
            <tr><td>&#128115;&#127997; {{ __('lecturing_schedule.friday_lecturer') }}</td><td>{{ $lecturingSchedule->lecturer }}</td></tr>
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
    </table>
</div>
