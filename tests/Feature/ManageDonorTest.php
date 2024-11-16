<?php

namespace Tests\Feature;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageDonorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_donor_list_in_donor_index_page()
    {
        config(['partners.partner_types' => 'donatur|Donatur']);
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['type_code' => 'donatur', 'creator_id' => $creator->id]);
        $this->visitRoute('donors.index');

        $this->seeText($partner->name);
    }

    /** @test */
    public function user_can_see_donor_detail()
    {
        config(['partners.partner_types' => 'donatur|Donatur']);
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['type_code' => 'donatur', 'creator_id' => $creator->id]);
        $this->visitRoute('donors.index');
        $this->seeElement('a', ['id' => 'show-partner-'.$partner->id]);

        $this->click('show-partner-'.$partner->id);

        $this->seeRouteIs('donors.show', $partner);
        $this->seeText($partner->name);
    }
}
