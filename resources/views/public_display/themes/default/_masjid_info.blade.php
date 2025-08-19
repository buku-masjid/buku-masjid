<div class="flex items-center p-2 overflow-hidden lg:h-full">
    @if (Setting::get('masjid_logo_path'))
        <div class="w-1/3 md:w-1/4 p-3">
            <img src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" class="w-full">
        </div>
    @endif
    <div class="overflow-hidden">
        <div class="mb-2 text-2xl 2xl:text-[1.5vw] font-bold p-2">
            {{ Setting::get('masjid_name', config('masjid.name')) }}
        </div>
        @if (Setting::get('masjid_address'))
        <div class="text-sm hidden text-gray-500">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}, {{Setting::get('masjid_city_name')}}</div>
        @endif
    </div>
</div>
