<div class="fs-4 pt-3 pb-3 row">
    <div class="col"><span class="fs-2 fw-bold pe-2">{{ __('lecturing.public_schedule') }} {{ __('time.'.$this->dayTitle) }}</span></div>
    <div class="col text-end"><a href="{{ route('public_schedules.this_week') }}">{{ __('app.show') }} <i class="ti">&#xea61;</i></a></div>
</div>
<div class="row">
@forelse($lecturings as $index => $lecturing)
    <div class="col-lg-6 mb-4">
        <div>
            <div class="card p-3">
                <div class="text-secondary lh-1">{{ __('lecturing.audience_' . $lecturing->audience_code) }}</div>
                @if ($lecturing->title)
                    <div class="lh-1 pt-3">
                        <h6 class="text-secondary m-0">{{ __('lecturing.title') }}</h6>
                        <p class="fs-2 mb-0">{{ $lecturing->title }}</p>
                    </div>
                @endif
                <div class="lh-1 pt-3">
                    <h6 class="text-secondary m-0">
                        @if ($lecturing->audience_code === $audienceFriday)
                            @if ($lecturing->lecturer_name == $lecturing->imam_name)
                                {{ __('lecturing.friday_lecturer_and_imam') }}
                            @else
                                {{ __('lecturing.friday_lecturer_name') }}
                            @endif
                        @else
                            {{ __('lecturing.lecturer_name') }}
                        @endif
                    </h6>
                    <div class="bm-txt-primary fw-bold">{{ $lecturing->lecturer_name }}</div>
                </div>
                @if ($lecturing->imam_name && $lecturing->lecturer_name != $lecturing->imam_name)
                    <div class="lh-1 pt-3">
                        <h6 class="text-secondary m-0">{{ __('lecturing.imam_name') }}</h6>
                        <div class="text-secondary fw-bold">{{ $lecturing->imam_name }}</div>
                    </div>
                @endif
                @if ($lecturing->muadzin_name)
                    <div class="lh-1 pt-3">
                        <h6 class="text-secondary m-0">{{ __('lecturing.muadzin_name') }}</h6>
                        <div class="text-secondary fw-bold">{{ $lecturing->muadzin_name }}</div>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-secondary fs-5 row px-3 py-2">
            <div class="col-auto"><i class="ti">&#xea52;</i> {{ $lecturing->day_name }}, {{ $lecturing->full_date }}</div>
            @if ($lecturing->audience_code === $audienceFriday)
                <div class="col-auto">
                    <i class="ti">&#xf319;</i>
                    {{ __('lecturing.adzan_time') }} {{ $lecturing->start_time }}
                </div>
            @else
                <div class="col-auto">
                    <i class="ti">&#xf319;</i>
                    {{ $lecturing->audience_code === $audienceFriday ? $lecturing->start_time : $lecturing->time }}
                    {{ $lecturing->time_text ? '('.$lecturing->time_text.')'  : '' }}
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="col mb-4">
        <img src="{{ asset('images/empty_lecturings.png') }}" style="border-radius: 15px; border: 1px solid #eee; padding-left: 0px; padding-right: 0px" title="{{ __('lecturing.empty').' '.__('time.'.$this->dayTitle) }}">
    </div>
@endforelse
</div>
