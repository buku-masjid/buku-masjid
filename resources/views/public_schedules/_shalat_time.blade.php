<div class="pt-4 pt-sm-0">
    <div class="d-flex align-items-end gap-2 align-items-end overflow-scroll">
        <div id="Imsak" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 240px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat; background-position: 0 -30px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.imsak') }}</h4>
                    <h1 class="m-0 d-block" data-time="imsak" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div id="Subuh" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -95px -70px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.fajr') }}</h4>
                    <h1 class="m-0 d-block" data-time="fajr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div id="Dzuhur" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 210px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -190px -60px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.dzuhr') }}</h4>
                    <h1 class="m-0 d-block" data-time="dzuhr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div id="Ashar" class=" pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 270px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -285px 0px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.ashr') }}</h4>
                    <h1 class="m-0 d-block" data-time="ashr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div id="Maghrib" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -380px -70px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.maghrib') }}</h4>
                    <h1 class="m-0 d-block" data-time="maghrib" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div id="Isya" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 230px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -475px -40px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.isya') }}</h4>
                    <h1 class="m-0 d-block" data-time="isya" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
    @if (config('features.shalat_time.is_active'))
        @php
            $shalatTimeProviderKey = config('shalat_time.default_provider');
            $shalatTimeProviderName = config('shalat_time.providers.'.$shalatTimeProviderKey.'.name');
            $shalatTimeProviderWebsiteUrl = config('shalat_time.providers.'.$shalatTimeProviderKey.'.website_url');
        @endphp
        <div class="pt-3">
            <div class="row">
                <div class="col-sm-6 pb-3 pb-sm-0">
                    <div class="text-center text-sm-start text-secondary fs-5">
                        <i class="ti">&#xea52;</i> {{ now()->isoFormat('dddd, DD MMMM Y') }}<br>
                        <i class="ti">&#xeae8;</i> <span class="text-capitalize" id="region_name"></span><br>
                        <i class="ti">&#xeade;</i> {{ __('shalat_time.source') }}: {{ link_to($shalatTimeProviderWebsiteUrl, $shalatTimeProviderName, ['target' => '_blank']) }}
                    </div>
                </div>
                <div class="col-sm-6 text-center text-sm-end lh-normal bm-txt-primary">
                    <div class="d-flex align-items-center justify-content-center justify-content-sm-end"><span id="timeRemaining" class="fs-2 fw-bold"></span></div>
                    <div><span class="fs-5 text-secondary">{{ __('shalat_time.time_before_text') }}</span> <span id="timeID" class="fw-bold"></span></div></a>
                </div>
            </div>
        </div>
    @endif
</div>

@if (config('features.shalat_time.is_active'))
@push('scripts')
<script>
    const cacheKey = `shalat_times_{{ now()->format('Ymd') }}`;
    const cachedData = localStorage.getItem(cacheKey);
    const shalatDailySchedule = {!! json_encode(__('shalat_time.daily_schedules')) !!};

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

    function updateElementsContent(shalatTimeData) {
        document.getElementById('region_name').textContent = shalatTimeData.location.toLowerCase() + ', ' + shalatTimeData.region.toLowerCase();
        document.querySelectorAll("[data-time]").forEach((element) => {
            element.textContent = shalatTimeData.schedules[element.dataset.time];
        });

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        nextShalatTime = 'imsak';
        // Ref: https://www.geeksforgeeks.org/how-to-get-a-key-in-a-javascript-object-by-its-value
        for (let prop in shalatTimeData.schedules) {
            const value = shalatTimeData.schedules[prop];
            if (value.match(/^\d{2,}:\d{2}$/)) {
                const [hour, minute] = value.split(":").map(Number);
                if (hour * 60 + minute > currentMinutes) {
                    nextShalatTime = prop;
                    break;
                }
            }
        }

        document.getElementById('timeID').textContent = shalatDailySchedule[nextShalatTime];

        const [nextHour, nextMinute] = shalatTimeData.schedules[nextShalatTime].split(":").map(Number);
        const nextMinutes = nextHour * 60 + nextMinute;

        // hitung sisa waktu
        let remainingMinutes = nextMinutes - currentMinutes;
        if (remainingMinutes < 0) {
            remainingMinutes += 24 * 60;
        }

        const hoursLeft = Math.floor(remainingMinutes / 60);
        const minutesLeft = remainingMinutes % 60;

        document.getElementById('timeRemaining').textContent = hoursLeft +" {{ __('time.hours') }} : "+ minutesLeft +" {{ __('time.minutes') }}";
    }
</script>
@endpush
@endif
