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
    public function partner_model_has_name_phone_attribute()
    {
        $partner = factory(Partner::class)->make(['name' => 'Abdullah', 'phone' => '081234567890', 'is_active' => 1]);
        $this->assertEquals('Abdullah (081234567890)', $partner->name_phone);

        $partner->phone = null;
        $this->assertEquals('Abdullah', $partner->name_phone);
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

    /** @test */
    public function partner_model_has_get_available_levels_method()
    {
        $partner = factory(Partner::class)->make();
        config(['partners.partner_levels' => '']);
        $this->assertEquals([], $partner->getAvailableLevels('donatur'));

        config(['partners.partner_levels' => 'donatur:donatur_tetap|Donatur Tetap|terdaftar|Terdaftar']);
        $this->assertEquals([
            'donatur_tetap' => 'Donatur Tetap',
            'terdaftar' => 'Terdaftar',
        ], $partner->getAvailableLevels('donatur'));

        config(['partners.partner_levels' => 'donatur:donatur_tetap|Donatur Tetap|terdaftar|Terdaftar,santri:1st|Kelas 1|2nd|Kelas 2|3rd|Kelas 3']);
        $this->assertEquals([
            '1st' => 'Kelas 1',
            '2nd' => 'Kelas 2',
            '3rd' => 'Kelas 3',
        ], $partner->getAvailableLevels('santri'));
    }

    /** @test */
    public function partner_model_has_level_attribute()
    {
        config(['partners.partner_types' => 'santri|Santri']);
        $partner = factory(Partner::class)->make(['type_code' => 'santri']);

        $this->assertEquals(null, $partner->level);

        $partner->level_code = 'gold';
        $this->assertEquals('gold', $partner->level);

        config(['partners.partner_levels' => 'santri:silver|Silver|gold|Gold|platinum|Platinum']);
        $partner->level_code = 'gold';
        $this->assertEquals('Gold', $partner->level);
    }

    /** @test */
    public function partner_model_has_gender_attribute()
    {
        $partner = factory(Partner::class)->make(['gender_code' => null]);

        $this->assertEquals(null, $partner->level);

        $partner->gender_code = 'm';
        $this->assertEquals(__('app.gender_male'), $partner->gender);

        $partner->gender_code = 'f';
        $this->assertEquals(__('app.gender_female'), $partner->gender);

        $partner->gender_code = 'something';
        $this->assertEquals('something', $partner->gender);
    }
}
