
<div class="jm-left-column pe-3 align-items-center justify-content-center jm-card me-4 text-center" >
        
    @if (config('features.shalat_time.is_active'))
        @php
            $shalatTimeProviderKey = config('shalat_time.default_provider');
            $shalatTimeProviderName = config('shalat_time.providers.'.$shalatTimeProviderKey.'.name');
            $shalatTimeProviderWebsiteUrl = config('shalat_time.providers.'.$shalatTimeProviderKey.'.website_url');
        @endphp
            <span class="display-6 fw-bold">Menuju waktu <span id="timeID"></span><br>
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
    let currentSeconds = new Date().getSeconds();

    function updateTimeInfoNextShalat() {
        if (cachedData) {
            const shalatTimeData = JSON.parse(cachedData);
            updateElementsContent(shalatTimeData);
        } else {
            fetch("{{ route('api.public_shalat_time.show') }}")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error:", data.error);
                } else {
                    const shalatTimeData = data;
                    localStorage.setItem(cacheKey, JSON.stringify(shalatTimeData));
                    updateElementsContent(shalatTimeData);
                }
            });
        }
    }

    function updateElementsContent(shalatTimeData) {
        document.querySelectorAll("[data-time]").forEach((element) => {
            element.textContent = shalatTimeData.jadwal[element.dataset.time];
        });

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        const currentSeconds = 60 - now.getSeconds() ;
        let nextShalatTime = 'imsak';
        for (let prop in shalatTimeData.jadwal) {
            const value = shalatTimeData.jadwal[prop];
            if (value.match(/^\d{2,}:\d{2}$/)) {
                const [hour, minute] = value.split(":").map(Number);
                if (hour * 60 + minute > currentMinutes) {
                    nextShalatTime = prop;
                    break;
                }
            }
        }

        document.getElementById('timeID').textContent = shalatDailySchedule[nextShalatTime];
        
        const elements = document.getElementsByClassName("jm-card");
        for (let i = 0; i < elements.length; i++) {
            elements[i].classList.remove("jm-card-active");
        }
        
        const element = document.getElementById(nextShalatTime);
        element.classList.add("jm-card-active");

        const [nextHour, nextMinute] = shalatTimeData.jadwal[nextShalatTime].split(":").map(Number);
        const nextMinutes = nextHour * 60 + nextMinute;

        let remainingMinutes = nextMinutes - currentMinutes;
        if (remainingMinutes < 0) {
            remainingMinutes += 24 * 60;
        }

        const hoursLeft = Math.floor(remainingMinutes / 60);
        const minutesLeft = remainingMinutes % 60;

        function addLeadingZero(number) {
            return (number < 10 ? '0' : '') + number;
        }
        //let currentSeconds = new Date().getSeconds();
        
        document.getElementById('timeRemaining').textContent = addLeadingZero(hoursLeft) + " : " + addLeadingZero(minutesLeft) + " : " + addLeadingZero(currentSeconds); // Get seconds here!
    }

    updateTimeInfoNextShalat();
    setInterval(updateTimeInfoNextShalat, 1000);
</script>
@endpush
@endif 