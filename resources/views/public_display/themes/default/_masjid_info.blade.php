<div class="d-flex align-items-center p-2 overflow-hidden">
        @if (Setting::get('masjid_logo_path'))
            <div class="col-3 p-2 me-2">
                <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 100%">
            </div>
        @endif
        <div class="overflow-hidden">
            <div class="mb-2 jm-masjid-name">
                {{ Setting::get('masjid_name', config('masjid.name')) }}
            </div>
            @if (Setting::get('masjid_address'))
            <div class="pe-5 fs-6 text-black-50">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}<br>{{Setting::get('masjid_city_name')}}</div>
            @endif
        </div>
</div>
