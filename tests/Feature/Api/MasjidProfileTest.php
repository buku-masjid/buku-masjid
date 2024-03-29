<?php

namespace Tests\Feature\Api;

use Facades\App\Helpers\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasjidProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_masjid_details()
    {
        Setting::set('masjid_name', 'Masjid Ar-Rahman');
        Setting::set('masjid_address', 'Jln. Kalimantan, No. 20, Kota Banjarmasin');
        Setting::set('masjid_google_maps_link', 'https://maps.app.goo.gl/abcd');
        Setting::set('masjid_logo_path', uniqid().'.webp');

        $masjidName = Setting::get('masjid_name', config('masjid.name'));
        $masjidAddress = Setting::get('masjid_address');
        $masjidGoogleMapsLink = Setting::get('masjid_google_maps_link');
        $logoImageUrl = Setting::get('masjid_logo_path');

        $this->getJson(route('api.masjid_profile.show'));

        $this->seeJson([
            'masjid_name' => $masjidName,
            'masjid_address' => $masjidAddress,
            'google_maps_link' => $masjidGoogleMapsLink,
            'logo_image_url' => Storage::url($logoImageUrl),
        ]);
    }
}
