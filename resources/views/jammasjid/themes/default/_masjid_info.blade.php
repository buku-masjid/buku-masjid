<div class="d-flex align-items-center p-2">
        @if (Setting::get('masjid_logo_path'))
            <div class="col-3 p-2 me-2">
                <a href="{{ url('/jammasjid') }}">
                    <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 100%">
                </a>
            </div>
        @endif
        <div >
            <div class="mb-2">
                <a class="fs-1 fw-bold lh-sm text-dark" href="{{ url('/jammasjid') }}">{{ Setting::get('masjid_name', config('masjid.name')) }}</a>
            </div>
            @if (Setting::get('masjid_address'))
            <div class="pe-5 fs-6 text-black-50">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}<br>{{Setting::get('masjid_city_name')}}</div>
            @endif
        </div>
</div> 