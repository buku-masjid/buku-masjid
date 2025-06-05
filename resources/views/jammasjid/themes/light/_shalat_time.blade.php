
<div class="jm-left-column pe-3 align-items-center justify-content-center jm-card me-4 text-center" >
        
    @if (config('features.shalat_time.is_active'))
        @php
            $shalatTimeProviderKey = config('shalat_time.default_provider');
            $shalatTimeProviderName = config('shalat_time.providers.'.$shalatTimeProviderKey.'.name');
            $shalatTimeProviderWebsiteUrl = config('shalat_time.providers.'.$shalatTimeProviderKey.'.website_url');
        @endphp
            <span class="display-6 fw-bold">Hitung mundur waktu <span id="timeID"></span><br>
            <span id="timeRemaining" class="fw-bolder jm-timeremaining bm-txt-primary"></span>
    @endif
            
</div>
<div class="jm-right-column">
    <div class="jm-card w-full p-3 me-3" id="imsak">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.imsak') }}</h4>
                    <h1 class="m-0 d-block" data-time="imsak" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3 me-3" id="fajr">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.fajr') }}</h4>
                    <h1 class="m-0 d-block" data-time="fajr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3 me-3" id="sunrise">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.sunrise') }}</h4>
                    <h1 class="m-0 d-block" data-time="sunrise" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3 me-3" id="dzuhr">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.dzuhr') }}</h4>
                    <h1 class="m-0 d-block" data-time="dzuhr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3 me-3" id="ashr">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.ashr') }}</h4>
                    <h1 class="m-0 d-block" data-time="ashr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3 me-3" id="maghrib">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="">{{ __('shalat_time.daily_schedules.maghrib') }}</h4>
                    <h1 class="m-0 d-block" data-time="maghrib" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    <div class="jm-card w-full p-3" id="isya">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center timeSholat">
            @if (config('features.shalat_time.is_active'))
                <h4 class="">{{ __('shalat_time.daily_schedules.isya') }}</h4>
                <h1 class="m-0 d-block" data-time="isya" style="min-width: 76px">--.--</h1>
            @else
                <div style="min-width: 76px">&nbsp;</div>
            @endif
            </div>
        </div>
    </div>
</div>


@if (config('features.shalat_time.is_active'))
@push('scripts')
<script>
    const cacheKey = `shalat_times_{{ now()->format('Ymd') }}`;
    const cachedData = localStorage.getItem(cacheKey);
    const shalatDailySchedule = JSON.parse('{!! json_encode(__("shalat_time.daily_schedules")) !!}')
    const shalatTimeData = "";
</script>
@include('jammasjid._shalat_info_js')
@endpush
@endif
