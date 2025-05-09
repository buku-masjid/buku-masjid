<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MasjidProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_can_visit_masjid_profile_page()
    {
        $user = $this->loginAsUser();
        $this->visitRoute('masjid_profile.show');
        $this->seeRouteIs('masjid_profile.show');
    }

    /** @test */
    public function admin_user_can_update_masjid_profile_data()
    {
        $user = $this->loginAsUser();

        $this->visitRoute('masjid_profile.edit');

        $this->submitForm(__('masjid_profile.update'), [
            'masjid_name' => 'Masjid Ar-Rahman',
            'masjid_address' => 'Jln. Kalimantan, No. 20, Kota Banjarmasin',
            'masjid_city_name' => 'Banjarmasin',
            'masjid_google_maps_link' => 'https://maps.app.goo.gl/abcd',
            'masjid_whatsapp_number' => '6281234567890',
            'masjid_instagram_username' => 'abcda.123',
            'masjid_youtube_username' => '@abcd-111',
            'masjid_facebook_username' => 'abcd_123',
            'masjid_telegram_username' => 'abcdaaa',
        ]);

        $this->see(__('masjid_profile.updated'));
        $this->seeRouteIs('masjid_profile.show');

        $this->seeInDatabase('settings', [
            'key' => 'masjid_name',
            'value' => 'Masjid Ar-Rahman',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_address',
            'value' => 'Jln. Kalimantan, No. 20, Kota Banjarmasin',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_city_name',
            'value' => 'Banjarmasin',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_google_maps_link',
            'value' => 'https://maps.app.goo.gl/abcd',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_whatsapp_number',
            'value' => '6281234567890',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_instagram_username',
            'value' => 'abcda.123',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_youtube_username',
            'value' => '@abcd-111',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_facebook_username',
            'value' => 'abcd_123',
        ]);
        $this->seeInDatabase('settings', [
            'key' => 'masjid_telegram_username',
            'value' => 'abcdaaa',
        ]);
    }

    /** @test */
    public function user_can_get_masjid_map_if_google_maps_link_exists()
    {
        DB::table('settings')->insert([
            'key' => 'masjid_google_maps_link',
            'value' => 'https://maps.app.goo.gl/viUfQtHqjUXJHSLb8',
        ]);
        Http::fake([
            'https://maps.app.goo.gl/viUfQtHqjUXJHSLb8' => Http::response('', 302, [
                'Location' => 'https://www.google.com/maps/@-3.4331567,114.8409041,15z',
            ]),
        ]);

        $user = $this->loginAsUser();
        $this->visitRoute('masjid_profile.show');
        $this->seeElement('button', ['type' => 'submit', 'id' => 'refresh_masjid_map']);
        $this->press('refresh_masjid_map');

        $this->seeInDatabase('settings', [
            'key' => 'masjid_latitude',
            'value' => '-3.4331567',
        ]);

        $this->seeInDatabase('settings', [
            'key' => 'masjid_longitude',
            'value' => '114.8409041',
        ]);
    }

    /** @test */
    public function user_failed_to_get_masjid_map_if_google_maps_link_is_not_found()
    {
        DB::table('settings')->insert([
            'key' => 'masjid_google_maps_link',
            'value' => 'https://maps.app.goo.gl/viUfQtHqjUXJHSLb8',
        ]);
        Http::fake([
            'https://maps.app.goo.gl/viUfQtHqjUXJHSLb8' => Http::response('', 404),
        ]);

        $user = $this->loginAsUser();
        $this->visitRoute('masjid_profile.show');
        $this->seeElement('button', ['type' => 'submit', 'id' => 'refresh_masjid_map']);
        $this->press('refresh_masjid_map');

        $this->seeInDatabase('settings', [
            'key' => 'masjid_latitude',
            'value' => null,
        ]);

        $this->seeInDatabase('settings', [
            'key' => 'masjid_longitude',
            'value' => null,
        ]);
    }
}
