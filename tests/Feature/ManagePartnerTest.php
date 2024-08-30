<?php

namespace Tests\Feature;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagePartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_partner_list_in_partner_index_page()
    {
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $creator->id]);
        $this->visitRoute('partners.index');

        $this->seeText($partner->name);
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
            'phone' => '1234567890',
            'work' => 'Dokter',
            'description' => 'Partner 1 description',
            'address' => 'Partner 1 address',
        ]);

        $this->seeRouteIs('partners.index');

        $this->seeInDatabase('partners', [
            'name' => 'Partner 1 name',
            'phone' => '1234567890',
            'work' => 'Dokter',
            'description' => 'Partner 1 description',
            'address' => 'Partner 1 address',
        ]);
    }

    /** @test */
    public function user_can_see_partner_detail()
    {
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $creator->id]);

        $this->visitRoute('partners.index');
        $this->seeElement('a', ['id' => 'show-partner-'.$partner->id]);

        $this->click('show-partner-'.$partner->id);

        $this->seeRouteIs('partners.show', $partner);
        $this->seeText($partner->name);
    }

    /** @test */
    public function user_can_edit_a_partner()
    {
        $creator = $this->loginAsUser();

        $partner = factory(Partner::class)->create(['creator_id' => $creator->id]);

        $this->visitRoute('partners.index');
        $this->click('edit-partner-1');

        $this->seeRouteIs('partners.index', [
            'action' => 'edit', 'id' => $partner->id,
        ]);

        $this->submitForm(__('partner.update'), [
            'name' => 'Partner 2 name',
            'phone' => '1234567890',
            'work' => 'Dokter',
            'description' => 'Partner 2 description',
            'address' => 'Partner 2 address',
            'is_active' => 0,
        ]);

        $this->seeRouteIs('partners.index');

        $this->seeInDatabase('partners', [
            'name' => 'Partner 2 name',
            'phone' => '1234567890',
            'work' => 'Dokter',
            'description' => 'Partner 2 description',
            'address' => 'Partner 2 address',
            'is_active' => 0,
        ]);
    }

    /** @test */
    public function user_can_delete_a_partner()
    {
        $creator = $this->loginAsUser();

        $partner = factory(Partner::class)->create(['creator_id' => $creator->id]);

        $this->visitRoute('partners.index');
        $this->click('edit-partner-1');
        $this->click('del-partner-'.$partner->id);

        $this->seeRouteIs('partners.index', [
            'action' => 'delete', 'id' => $partner->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);
    }
}
