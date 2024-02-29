<div class="page-header">
    <h3 class="page-title">{{ __('lecturing.public_schedule') }} {{ __('time.'.$this->dayTitle) }}</h3>
    <div class="page-options d-flex">
        @if (!$lecturings->isEmpty())
            <a class="btn btn-sm btn-success" href="{{ route('public_schedules.'.$this->dayTitle) }} "role="button">{{ __('app.show') }}</a>
        @endif
    </div>
</div>
@forelse($lecturings as $index => $lecturing)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('lecturing.audience_' . $lecturing->audience_code) }}
            </h3>
        </div>
        <table class="table table-sm mb-0">
            <tbody>
                <tr>
                    <td class="col-4">{!! config('lecturing.emoji.date') !!} {{ __('time.day_name') }}/{{ __('time.date') }}</td>
                    <td><strong>{{ $lecturing->day_name }}</strong>, {{ $lecturing->full_date }}</td>
                </tr>
                <tr>
                    <td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing.time') }}</td>
                    <td>
                        {{ $lecturing->audience_code === $audienceFriday ? $lecturing->start_time : $lecturing->time }}
                        {{ $lecturing->time_text ? '('.$lecturing->time_text.')'  : '' }}
                    </td>
                </tr>
                <tr>
                    <td>{!! config('lecturing.emoji.lecturer') !!} {{ $lecturerName[$lecturing->audience_code] }}</td>
                    <td>{{ $lecturing->lecturer_name }}</td>
                </tr>
                @if ($lecturing->imam_name)
                    <tr>
                        <td>{!! config('lecturing.emoji.imam') !!} {{ __('lecturing.imam_name') }}</td>
                        <td>{{ $lecturing->imam_name }} </td>
                    </tr>
                @endif
                @if ($lecturing->muadzin_name)
                    <tr>
                        <td>{!! config('lecturing.emoji.muadzin') !!} {{ __('lecturing.muadzin_name') }}</td>
                        <td>{{ $lecturing->muadzin_name }} </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@empty
    <p>{{ __('lecturing.empty') }} {{ __('time.'.$this->dayTitle) }}.</p>
@endforelse
