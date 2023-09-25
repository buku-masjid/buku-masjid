<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'masjid_google_maps_link' => 'https://maps.app.goo.gl/abcd',
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
            'key' => 'masjid_google_maps_link',
            'value' => 'https://maps.app.goo.gl/abcd',
        ]);
    }
}
