<?php

namespace Tests\Unit\Models;

use App\Models\Partner;
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
}
