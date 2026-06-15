
<div class="md:w-1/3 lg:w-1/4 px-3 py-5 flex flex-col items-center justify-center jm-card-remaining text-center lg:h-full space-y-3 mb-2" >
    @if (config('features.shalat_time.is_active'))
        <div class="text-[1.3vw] bm-txt-primary font-semibold">{{ __('shalat_time.time_before_text') }} <span id="timeID"></span></div>
        <div id="timeRemaining" class="font-extrabold leading-none text-[3.2vw] bm-txt-primary"></div>   
    @endif
</div>
<div class="md:w-2/3 lg:w-3/4 lg:ps-2 grid grid-cols-2 lg:grid-cols-7 gap-4 text-gray-500">
    <div class="jm-card w-full p-3" id="imsak">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.imsak') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="imsak">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3 mr-3" id="fajr">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.fajr') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="fajr">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3 mr-3" id="sunrise">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.sunrise') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="sunrise">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3 mr-3" id="dzuhr">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.dzuhr') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="dzuhr">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3 mr-3" id="ashr">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.ashr') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="ashr">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3 mr-3" id="maghrib">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.maghrib') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="maghrib">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
    <div class="jm-card w-full p-3" id="isya">
        <div class="h-full flex items-center flex-col justify-center space-y-2">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 text-[1.5vw]">{{ __('shalat_time.daily_schedules.isya') }}</h4>
                    <h1 class="m-0 block text-3xl lg:text-[2vw] xl:text-[2.1vw] 2xl:text-[2.3vw] font-bold" data-time="isya">--.--</h1>
                @else
                    <div class="min-w-[76px]">&nbsp;</div>
                @endif
        </div>
    </div>
</div>
