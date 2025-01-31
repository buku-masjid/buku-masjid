<?php

namespace Tests\Feature\Api;

use Facades\App\Helpers\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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

    /** @test */
    public function update_masjid_logo_with_csrf_token()
    {
        $this->loginAsUser();
        $this->dontSeeInDatabase('settings', ['key' => 'masjid_logo_path']);

        $this->get(route('home'));
        $this->seeStatusCode(200);

        $csrfToken = csrf_token();
        Storage::fake(config('filesystem.default'));
        $image = UploadedFile::fake()->image('logo.jpg');
        $base64Image = 'data:image/png;base64,'.base64_encode(file_get_contents($image->getPathname()));

        $this->post(route('api.masjid_profile.upload_logo'), [
            '_token' => $csrfToken,
            'image' => $base64Image,
        ]);

        $this->seeStatusCode(200);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_logo_path',
        ]);

        $settingRecord = DB::table('settings')->where('key', 'masjid_logo_path')->first();
        Storage::assertExists($settingRecord->value);
        $this->seeJson([
            'message' => __('masjid_profile.logo_uploaded'),
            'image' => Storage::url($settingRecord->value),
        ]);
    }

    /** @test */
    public function update_masjid_photo_with_csrf_token()
    {
        $this->loginAsUser();
        $this->dontSeeInDatabase('settings', ['key' => 'masjid_photo_path']);

        $this->get(route('home'));
        $this->seeStatusCode(200);

        $csrfToken = csrf_token();
        Storage::fake(config('filesystem.default'));
        $image = UploadedFile::fake()->image('photo.jpg');
        $base64Image = 'data:image/png;base64,'.base64_encode(file_get_contents($image->getPathname()));

        $this->post(route('api.masjid_profile.upload_photo'), [
            '_token' => $csrfToken,
            'image' => $base64Image,
        ]);

        $this->seeStatusCode(200);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_photo_path',
        ]);

        $settingRecord = DB::table('settings')->where('key', 'masjid_photo_path')->first();
        Storage::assertExists($settingRecord->value);
        $this->seeJson([
            'message' => __('masjid_profile.photo_uploaded'),
            'image' => Storage::url($settingRecord->value),
        ]);
    }
}
