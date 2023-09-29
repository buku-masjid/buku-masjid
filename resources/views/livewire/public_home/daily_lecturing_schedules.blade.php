<div class="page-header">
    <h3 class="page-title">{{ __('lecturing_schedule.public_schedule') }} {{ __('time.'.$this->dayTitle) }}</h3>
    <div class="page-options d-flex">
        @if (!$lecturingSchedules->isEmpty())
            <a class="btn btn-sm btn-success" href="{{ route('public_schedules.'.$this->dayTitle) }} "role="button">{{ __('app.show') }}</a>
        @endif
    </div>
</div>
@forelse($lecturingSchedules as $index => $lecturingSchedule)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('lecturing_schedule.audience_' . $lecturingSchedule->audience_code) }}
            </h3>
        </div>
        <table class="table table-sm mb-0">
            <tbody>
                @if ($lecturingSchedule->audience_code != $audienceFriday)
                    <tr>
                        <td class="col-4">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing_schedule.lecturing') }}</td>
                        <td><strong>{{ $lecturingSchedule->day_name }}, {{ $lecturingSchedule->time_text }}</strong>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing_schedule.time') }}</td>
                    <td>{{ $lecturingSchedule->audience_code === $audienceFriday ? $lecturingSchedule->start_time : $lecturingSchedule->time }}
                    </td>
                </tr>
                <tr>
                    <td>{!! config('lecturing.emoji.lecturer') !!} {{ $lecturerName[$lecturingSchedule->audience_code] }}</td>
                    <td>{{ $lecturingSchedule->lecturer_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@empty
    <p>{{ __('lecturing_schedule.empty') }} {{ __('time.'.$this->dayTitle) }}.</p>
@endforelse
