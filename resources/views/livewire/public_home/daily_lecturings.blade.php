{{--<div class="page-header">
    <h3 class="page-title">{{ __('lecturing.public_schedule') }} {{ __('time.'.$this->dayTitle) }}</h3>
    <div class="page-options d-flex">
        @if (!$lecturings->isEmpty())
            <a class="btn btn-sm btn-success" href="{{ route('public_schedules.'.$this->dayTitle) }} "role="button">{{ __('app.show') }}</a>
        @endif
    </div>
</div>--}}

<div class="fs-4 pt-3 pb-3 d-flex align-items-center row">
    <div class="col"><span class="fs-2 fw-bold pe-2">Kajian hari ini</span></div>
    <div class="col"><a href="{{ route('public_schedules.'.$this->dayTitle) }}"><span class="pe-2 float-end">Detil Kajian <i class="ti">&#xea61;</i></span></a></div>
</div>
<div class="row">
@forelse($lecturings as $index => $lecturing)
    <div class="col-lg-6 mb-4">
        <div>
            <div class="card p-3">
                <div class="text-secondary lh-1">Pembahasan</div>
                <p class="fs-2"> Use the scaling classes for larger or smaller</p>
                <div class="lh-1 pt-3">
                    <h6 class="text-secondary m-0">{{ $lecturerName[$lecturing->audience_code] }}</h6>
                    <div class="bm-txt-primary fw-bold">{{ $lecturing->lecturer_name }}</div>
                </div>
            </div>
        </div>
        <div class="text-secondary fs-5 row px-3 py-2">
            <div class="col-auto"><i class="ti">&#xea52;</i>  {{ __('lecturing.audience_' . $lecturing->audience_code) }}</div>
            <div class="col-auto"><i class="ti">&#xea52;</i>  {{ $lecturing->day_name }}, {{ $lecturing->date }}</div>
            <div class="col-auto"><i class="ti">&#xf319;</i> {{ $lecturing->audience_code === $audienceFriday ? $lecturing->start_time : $lecturing->time }}
            {{ $lecturing->time_text ? '('.$lecturing->time_text.')'  : '' }}</div>
        </div>
    </div>

    {{--<div class="card">
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
    </div> --}}
@empty
    <p>{{ __('lecturing.empty') }} {{ __('time.'.$this->dayTitle) }}.</p>
@endforelse
</div>