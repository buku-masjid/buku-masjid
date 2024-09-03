<?php

namespace Tests\Unit\Policies;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_partner_list()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('view-any', new Partner));
        $this->assertTrue($chairman->can('view-any', new Partner));
        $this->assertTrue($secretary->can('view-any', new Partner));
        $this->assertTrue($finance->can('view-any', new Partner));
    }

    /** @test */
    public function user_can_create_partner()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new Partner));
        $this->assertTrue($chairman->can('create', new Partner));
        $this->assertTrue($secretary->can('create', new Partner));
        $this->assertTrue($finance->can('create', new Partner));
    }

    /** @test */
    public function user_can_see_partner_details()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $partner = factory(Partner::class)->create(['creator_id' => $admin->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($admin->can('view', $partner));
        $this->assertTrue($admin->can('view', $othersPartner));
        $this->assertTrue($chairman->can('view', $partner));
        $this->assertTrue($secretary->can('view', $partner));
        $this->assertTrue($finance->can('view', $partner));
    }

    /** @test */
    public function admin_and_finance_can_update_partner()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $partner = factory(Partner::class)->create(['creator_id' => $admin->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($admin->can('update', $partner));
        $this->assertTrue($admin->can('update', $othersPartner));
        $this->assertTrue($chairman->can('update', $partner));
        $this->assertTrue($secretary->can('update', $partner));
        $this->assertTrue($finance->can('update', $partner));
    }

    /** @test */
    public function admin_and_finance_can_delete_partner()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $partner = factory(Partner::class)->create(['creator_id' => $admin->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($admin->can('delete', $partner));
        $this->assertTrue($admin->can('delete', $othersPartner));
        $this->assertFalse($chairman->can('delete', $partner));
        $this->assertFalse($secretary->can('delete', $partner));
        $this->assertFalse($finance->can('delete', $partner));
    }
}
