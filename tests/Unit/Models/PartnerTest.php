<?php

namespace Tests\Unit\Models;

use App\Models\Partner;
use App\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_partner_has_status_attribute()
    {
        $partner = factory(Partner::class)->make(['is_active' => 1]);
        $this->assertEquals(__('app.active'), $partner->status);

        $partner->is_active = 0;
        $this->assertEquals(__('app.inactive'), $partner->status);
    }

    /** @test */
    public function partner_model_has_has_many_transactions_relation()
    {
        $partner = factory(Partner::class)->create();
        $transaction = factory(Transaction::class)->create([
            'partner_id' => $partner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $partner->transactions);
        $this->assertInstanceOf(Transaction::class, $partner->transactions->first());
    }

    /** @test */
    public function partner_model_has_get_available_types_method()
    {
        $partner = factory(Partner::class)->make();
        config(['partners.partner_types' => '']);
        $this->assertEquals(['partner' => 'Partner'], $partner->getAvailableTypes());

        config(['partners.partner_types' => 'donatur|Donatur']);
        $this->assertEquals(['donatur' => 'Donatur'], $partner->getAvailableTypes());

        config(['partners.partner_types' => 'donatur|Donatur,santri|Santri']);
        $this->assertEquals([
            'donatur' => 'Donatur',
            'santri' => 'Santri',
        ], $partner->getAvailableTypes());
    }

    /** @test */
    public function partner_model_has_type_attribute()
    {
        $partner = factory(Partner::class)->make();

        $this->assertEquals(__('partner.partner'), $partner->type);

        $partner->type_code = 'santri';
        $this->assertEquals('santri', $partner->type);

        config(['partners.partner_types' => 'santri|Santri']);
        $partner->type_code = 'santri';
        $this->assertEquals('Santri', $partner->type);
    }
}
