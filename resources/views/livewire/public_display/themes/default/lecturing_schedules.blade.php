@if ($lecturings->isEmpty())
    <div class="hidden"></div>
@else
    <div class="carousel-slide flex-shrink-0 w-full h-full">
        <div class="jm-card w-full h-full flex flex-col p-6 2xl:p-8 overflow-hidden">
            <h2 class="text-[2.2vw] font-bold leading-none pb-4 2xl:pb-6">{{ __('lecturing.upcoming') }}</h2>
            <div class="flex flex-col flex-1 justify-start gap-3 2xl:gap-4">
                @foreach ($lecturings as $lecturing)
                    <div class="flex items-center bg-white bg-opacity-60 rounded-2xl px-5 py-3 2xl:py-4">
                        <div class="w-1/4 pe-4 leading-tight">
                            <div class="text-[1.4vw] font-bold">{{ $lecturing->day_name }}</div>
                            <div class="text-[1vw]">{{ $lecturing->full_date }}</div>
                            <div class="text-[1vw]">
                                @if ($lecturing->audience_code === $audienceFriday)
                                    {{ __('lecturing.adzan_time') }} {{ $lecturing->start_time }}
                                @else
                                    {{ $lecturing->time }}
                                @endif
                            </div>
                        </div>
                        <div class="w-3/4 ps-4 border-s-2 border-gray-400 leading-tight">
                            <div class="text-[1vw] uppercase">{{ __('lecturing.audience_'.$lecturing->audience_code) }}</div>
                            @if ($lecturing->title)
                                <div class="text-[1.4vw] font-bold">{{ $lecturing->title }}</div>
                            @endif
                            <div class="text-[1.2vw]">
                                {{ $lecturing->audience_code === $audienceFriday ? __('lecturing.friday_lecturer_name') : __('lecturing.lecturer_name') }}:
                                <span class="font-bold">{{ $lecturing->lecturer_name }}</span>
                                @if ($lecturing->time_text)
                                    <span>({{ $lecturing->time_text }})</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
