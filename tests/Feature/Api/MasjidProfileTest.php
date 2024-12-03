<?php

namespace Tests\Feature\Api;

use Facades\App\Helpers\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
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

    /** @test */
    public function admin_can_update_masjid_logo_image()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        Storage::fake(config('filesystem.default'));

        // Ref: https://www.geeksforgeeks.org/how-to-convert-an-image-to-base64-encoding-in-php/
        $imageContent = file_get_contents(public_path('images/icons8-coins-16.png'));
        $base64EncodedImage = 'data:image/png;base64,'.base64_encode($imageContent);

        $this->postJson(route('api.masjid_profile.image'), [
            'image' => $base64EncodedImage,
        ]);

        $this->seeInDatabase('settings', [
            'model_id' => null,
            'model_type' => null,
            'key' => 'masjid_logo_path',
        ]);

        $masjidLogoPath = DB::table('settings')->where('key', 'masjid_logo_path')->first()->value;
        Storage::assertExists($masjidLogoPath);
    }
}
