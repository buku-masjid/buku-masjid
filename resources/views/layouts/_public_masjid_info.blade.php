<div>
    @if (Setting::get('masjid_logo_path'))
        <div class="mb-3"><img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 150px"></div>
    @endif
    <div>
        <span class="fs-2">Assalamu'alaikum</span><br>
        <a class="fs-1 fw-bold lh-sm text-dark" href="{{ url('/') }}">{{ Setting::get('masjid_name', config('masjid.name')) }}</a>
    </div>
    @if (Setting::get('masjid_address'))
    <div class="mt-3 pe-5 fs-5 text-black-50">{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}</div>
    @endif
</div>
