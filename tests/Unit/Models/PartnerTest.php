<?php

namespace Tests\Unit\Models;

use App\Partner;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_partner_has_belongs_to_creator_relation()
    {
        $partner = factory(Partner::class)->make();

        $this->assertInstanceOf(User::class, $partner->creator);
        $this->assertEquals($partner->creator_id, $partner->creator->id);
    }

    /** @test */
    public function a_partner_has_for_user_scope()
    {
        $partnerOwner = $this->loginAsUser();
        $partner = factory(Partner::class)->create([
            'creator_id' => $partnerOwner->id,
        ]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertCount(1, Partner::all());
    }

    /** @test */
    public function a_partner_has_many_transactions_relation()
    {
        $partnerOwner = $this->loginAsUser();
        $partner = factory(Partner::class)->create([
            'creator_id' => $partnerOwner->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'partner_id' => $partner->id,
            'creator_id' => $partnerOwner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $partner->transactions);
        $this->assertInstanceOf(Transaction::class, $partner->transactions->first());
    }

    /** @test */
    public function a_partner_has_name_label_attribute()
    {
        $partner = factory(Partner::class)->make();

        $nameLabel = '<span class="badge badge-pill badge-secondary">'.$partner->name.'</span>';
        $this->assertEquals($nameLabel, $partner->name_label);
    }

    /** @test */
    public function a_partner_has_status_attribute()
    {
        $partner = factory(Partner::class)->make();
        $this->assertEquals(__('app.active'), $partner->status);

        $partner->status_id = Partner::STATUS_INACTIVE;
        $this->assertEquals(__('app.inactive'), $partner->status);
    }
}
