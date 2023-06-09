<?php

namespace Tests\Feature;

use App\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagePartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_partner_list_in_partner_index_page()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('partners.index');
        $this->see($partner->name);
    }

    /** @test */
    public function user_can_create_a_partner()
    {
        $this->loginAsUser();
        $this->visitRoute('partners.index');

        $this->click(__('partner.create'));
        $this->seeRouteIs('partners.index', ['action' => 'create']);

        $this->submitForm(__('partner.create'), [
            'name' => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ]);

        $this->seeRouteIs('partners.index');

        $this->seeInDatabase('partners', [
            'name' => 'Partner 1 name',
            'description' => 'Partner 1 description',
            'status_id' => Partner::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function user_can_edit_a_partner()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['name' => 'Testing 123', 'creator_id' => $user->id]);

        $this->visitRoute('partners.index');
        $this->click('edit-partner-'.$partner->id);
        $this->seeRouteIs('partners.index', ['action' => 'edit', 'id' => $partner->id]);

        $this->submitForm(__('partner.update'), [
            'name' => 'Partner 1 name',
            'description' => 'Partner 1 description',
            'status_id' => Partner::STATUS_ACTIVE,
        ]);

        $this->seeRouteIs('partners.index');

        $this->seeInDatabase('partners', [
            'name' => 'Partner 1 name',
            'description' => 'Partner 1 description',
            'status_id' => Partner::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function user_can_delete_a_partner()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        factory(Partner::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('partners.index', ['action' => 'edit', 'id' => $partner->id]);
        $this->click('del-partner-'.$partner->id);
        $this->seeRouteIs('partners.index', ['action' => 'delete', 'id' => $partner->id]);

        $this->seeInDatabase('partners', [
            'id' => $partner->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);
    }
}
