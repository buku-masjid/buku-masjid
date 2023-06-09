<?php

namespace Tests\Unit\Policies;

use App\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_partner()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Partner));
    }

    /** @test */
    public function user_can_only_view_their_own_partner_detail()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($user->can('view', $partner));
        $this->assertFalse($user->can('view', $othersPartner));
    }

    /** @test */
    public function user_can_only_update_their_own_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($user->can('update', $partner));
        $this->assertFalse($user->can('update', $othersPartner));
    }

    /** @test */
    public function user_can_only_delete_their_own_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertTrue($user->can('delete', $partner));
        $this->assertFalse($user->can('delete', $othersPartner));
    }
}
