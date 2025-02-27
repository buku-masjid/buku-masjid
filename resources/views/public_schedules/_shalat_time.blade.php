<div>
    <div class="d-flex align-items-end gap-2 align-items-end">
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 240px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat; background-position: 0 -30px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.imsak') }}</h4>
                    <h1 class="m-0 d-block" data-time="imsak" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -95px -70px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.fajr') }}</h4>
                    <h1 class="m-0 d-block" data-time="fajr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 210px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -190px -60px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.dzuhr') }}</h4>
                    <h1 class="m-0 d-block" data-time="dzuhr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 270px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -285px 0px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.ashr') }}</h4>
                    <h1 class="m-0 d-block" data-time="ashr" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -380px -70px">
            <div class="prayinfo">
                @if (config('features.shalat_time.is_active'))
                    <h4 class="m-0 d-flex">{{ __('shalat_time.daily_schedules.maghrib') }}</h4>
                    <h1 class="m-0 d-block" data-time="maghrib" style="min-width: 76px">--.--</h1>
                @else
                    <div style="min-width: 76px">&nbsp;</div>
                @endif
            </div>
        </div>
        <div class="bg-secondary praytime-item d-flex align-items-end col" style="height: 230px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -475px -40px">
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
        <div class="text-end fs-6 text-secondary pt-3">
            Hari / Tanggal: <span id="date_string"></span><br>
            Sumber: {{ link_to($shalatTimeProviderWebsiteUrl, $shalatTimeProviderName, ['target' => '_blank']) }}<br>
            Untuk wilayah <span id="region_name"></span><br>
            <span id="timeRemaining"></span> lagi menuju waktu <span id="timeID"></span>
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
        document.getElementById('date_string').textContent = shalatTimeData.jadwal.date_string;
        document.getElementById('region_name').textContent = shalatTimeData.lokasi + ', ' + shalatTimeData.daerah;
        document.querySelectorAll("[data-time]").forEach((element) => {
            element.textContent = shalatTimeData.jadwal[element.dataset.time];
        });

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        // Ref: https://www.geeksforgeeks.org/how-to-get-a-key-in-a-javascript-object-by-its-value
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

        const [nextHour, nextMinute] = shalatTimeData.jadwal[nextShalatTime].split(":").map(Number);
        const nextMinutes = nextHour * 60 + nextMinute;

        // hitung sisa waktu
        let remainingMinutes = nextMinutes - currentMinutes;
        if (remainingMinutes < 0) {
            remainingMinutes += 24 * 60;
        }

        const hoursLeft = Math.floor(remainingMinutes / 60);
        const minutesLeft = remainingMinutes % 60;

        document.getElementById('timeRemaining').textContent = hoursLeft +" Jam : "+ minutesLeft +" Menit";
    }
</script>
@endpush
@endif
