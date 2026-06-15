<div class="flex items-center p-2 overflow-hidden lg:h-full">
    @if (Setting::get('masjid_logo_path'))
        <div class="p-4">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" class="h-[8vh]">
        </div>
    @endif
    <div class="overflow-hidden">
        <div class="masjid-name text-[1.5vw] leading-none font-semibold py-2 px-4">
            {{ Setting::get('masjid_name', config('masjid.name')) }}
        </div>
        @if (Setting::get('masjid_address'))
        <div class="text-sm hidden text-gray-500 mt-2">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}, {{Setting::get('masjid_city_name')}}</div>
        @endif
    </div>
</div>
