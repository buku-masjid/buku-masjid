@if ($showLetterhead)
    <div class="text-center">
        <h3 class="uppercase" style="margin-bottom: 0;">
            {{ __('report.management') }}
            <br/>
            {{ Setting::get('masjid_name', config('masjid.name')) }}
        </h3>
        <div>{!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}</div>
        <hr style="margin-top: 0.5em"/>
    </div>
@endif
