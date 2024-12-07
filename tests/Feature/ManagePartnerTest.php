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

        $this->click(__('partner.create', ['type' => __('partner.partner')]));
        $this->seeRouteIs('partners.index', ['action' => 'create', 'type_code' => 'partner']);

        $this->submitForm(__('partner.create', ['type' => __('partner.partner')]), [
            'name' => 'Partner 1 name',
            'type_code' => 'partner',
            'phone' => '081234567890',
            'dob' => '2000-02-29',
            'pob' => 'Banjarmasin',
            'gender_code' => 'f',
            'work_id' => '',
            'work' => 'Dokter',
            'description' => 'Partner 1 description',
            'level_code' => '',
            'address' => 'Partner 1 address',
            'rt' => '001',
            'rw' => '001',
            'marital_status_id' => '1',
            'financial_status_id' => '1',
            'activity_status_id' => '1',
            'religion_id' => '1',
        ]);

        $this->seeRouteIs('partners.index', ['type_code' => 'partner']);

        $this->seeInDatabase('partners', [
            'name' => 'Partner 1 name',
            'type_code' => 'partner',
            'phone' => '081234567890',
            'dob' => '2000-02-29',
            'pob' => 'Banjarmasin',
            'gender_code' => 'f',
            'work_id' => null,
            'work' => 'Dokter',
            'description' => 'Partner 1 description',
            'type_code' => 'partner',
            'level_code' => null,
            'address' => 'Partner 1 address',
            'rt' => '001',
            'rw' => '001',
            'marital_status_id' => '1',
            'financial_status_id' => '1',
            'activity_status_id' => '1',
            'religion_id' => '1',
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
        config(['partners.partner_types' => 'donatur|Donatur']);
        config(['partners.partner_levels' => 'donatur:donatur_tetap|Donatur Tetap|terdaftar|Terdaftar']);
        $partner = factory(Partner::class)->create(['type_code' => 'donatur']);

        $this->visitRoute('partners.index');
        $this->click('edit-partner-1');

        $this->seeRouteIs('partners.index', [
            'action' => 'edit', 'id' => $partner->id, 'type_code' => $partner->type_code,
        ]);

        $this->submitForm(__('partner.update', ['type' => 'Donatur']), [
            'name' => 'Partner 2 name',
            'type_code' => 'donatur',
            'phone' => '081234567890',
            'dob' => '2000-02-29',
            'pob' => 'Banjarmasin',
            'gender_code' => 'm',
            'work_id' => '',
            'work' => 'Dokter',
            'description' => 'Partner 2 description',
            'address' => 'Partner 2 address',
            'rt' => '001',
            'rw' => '001',
            'marital_status_id' => '1',
            'financial_status_id' => '1',
            'activity_status_id' => '1',
            'religion_id' => '1',
            'level_code' => 'donatur_tetap',
            'is_active' => 0,
        ]);

        $this->seeRouteIs('partners.index', ['type_code' => 'donatur']);

        $this->seeInDatabase('partners', [
            'name' => 'Partner 2 name',
            'phone' => '081234567890',
            'dob' => '2000-02-29',
            'pob' => 'Banjarmasin',
            'gender_code' => 'm',
            'work_id' => null,
            'work' => 'Dokter',
            'description' => 'Partner 2 description',
            'address' => 'Partner 2 address',
            'rt' => '001',
            'rw' => '001',
            'marital_status_id' => '1',
            'financial_status_id' => '1',
            'activity_status_id' => '1',
            'religion_id' => '1',
            'type_code' => 'donatur',
            'level_code' => 'donatur_tetap',
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
            'action' => 'delete', 'id' => $partner->id, 'type_code' => $partner->type_code,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);
    }
}
