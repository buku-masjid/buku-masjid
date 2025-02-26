@if (Setting::get('masjid_whatsapp_number'))
    <div class="mt-3 pt-3 border-top">
        <span class="text-secondary">Whatsapp</span>
        <a href="https://wa.me/{{ Setting::get('masjid_whatsapp_number') }}" target="_blank">
            <h2><i class="ti">&#xec74;</i> {{ Setting::get('masjid_whatsapp_number') }}</h2>
        </a>
    </div>
@endif
<div class="mt-3 pt-3 border-top">
    @if (Setting::get('masjid_facebook_username'))
        <div class="mb-2">
            <a href="https://facebook.com/{{ Setting::get('masjid_facebook_username') }}" target="_blank">
                <i class="ti">&#xf7e6;</i> <span class="fs-4"> {{ Setting::get('masjid_facebook_username') }}</span>
            </a>
        </div>
    @endif
    @if (Setting::get('masjid_instagram_username'))
        <div class="mb-2">
            <a href="https://instagram.com/{{ Setting::get('masjid_instagram_username') }}" target="_blank">
                <i class="ti">&#xec20;</i> <span class="fs-4"> {{ Setting::get('masjid_instagram_username') }}</span>
            </a>
        </div>
    @endif
    @if (Setting::get('masjid_youtube_username'))
        <div class="mb-2">
            <a href="https://youtube.com/{{ Setting::get('masjid_youtube_username') }}" target="_blank">
                <i class="ti">&#xfc22;</i> <span class="fs-4"> {{ Setting::get('masjid_youtube_username') }}</span>
            </a>
        </div>
    @endif
    @if (Setting::get('masjid_telegram_username'))
        <div class="mb-2">
            <a href="https://t.me/{{ Setting::get('masjid_telegram_username') }}" target="_blank">
                <i class="ti ti-brand-telegram"></i> <span class="fs-4"> {{ Setting::get('masjid_telegram_username') }}</span>
            </a>
        </div>
    @endif
</div>
