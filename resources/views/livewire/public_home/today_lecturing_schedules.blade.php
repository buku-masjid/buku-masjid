<div class="card">
    <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @if ($lecturingSchedules->isEmpty())
            <div class="carousel-item active" data-interval="1000">
                <div class="card-header">
                    <h3 class="card-title flex-grow-1">{{ $header['public'] }}</h3>
                    <a class="btn btn-sm btn-success" href="{{ $linkDetailSchedule }}" role="button">{{ $detailTextButton }}</a>
                </div>
                <div class="card-body">
                    <p class>
                        {{ __('lecturing_schedule.today_empty') }}
                    </p>
                </div>
            </div>
            @else
            @foreach($lecturingSchedules as $index => $lecturingSchedule)
            <div class="carousel-item {{ $isFriday && $lecturingSchedule->audience_code === $audienceFriday ? 'active' : ($index === 0 && !$isFriday ? 'active' : '') }}" data-interval="{{ $intervalCarousel }}">
                <div class="card-header">
                    <h3 class="card-title flex-grow-1">{{ $header[$lecturingSchedule->audience_code] }}</h3>
                    <a class="btn btn-sm btn-success" href="{{ $linkDetailSchedule }}" role="button">{{ $detailTextButton }}</a>
                </div>
                <table class="table-sm mb-0 table-bordered w-100">
                    <tbody>
                        <tr>
                            <td class="col-4">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing_schedule.lecturing') }}</td>
                            <td><strong>{{ $lecturingSchedule->day_name }}, {{ $lecturingSchedule->audience_code === $audienceFriday ? $lecturingSchedule->full_date : $lecturingSchedule->time_text }}</strong></td>
                        </tr>
                        @if ($lecturingSchedule->audience_code !== $audienceFriday)
                        <tr>
                            <td>{!! config('lecturing.emoji.date') !!} {{ __('time.date') }}</td>
                            <td>{{ $lecturingSchedule->full_date }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing_schedule.time') }}</td>
                            <td>{{ $lecturingSchedule->audience_code === $audienceFriday ? $lecturingSchedule->start_time : $lecturingSchedule->time }}</td>
                        </tr>
                        <tr>
                            <td>{!! config('lecturing.emoji.lecturer') !!} {{ $lecturerName[$lecturingSchedule->audience_code] }}</td>
                            <td>{{ $lecturingSchedule->lecturer_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>