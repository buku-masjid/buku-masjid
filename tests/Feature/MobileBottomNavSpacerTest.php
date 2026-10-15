<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileBottomNavSpacerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function internal_pages_reserve_space_for_the_fixed_bottom_navigation(): void
    {
        $this->loginAsUser();
        factory(Book::class)->create();

        $this->visitRoute('home');
        $this->assertResponseOk();
        $this->seeElement('#mobile-nav-spacer');
        $this->seeElement('#mobile-nav-spacer.d-block.d-sm-none');

        $this->visitRoute('reports.finance.summary');
        $this->assertResponseOk();
        $this->seeElement('#mobile-nav-spacer.d-block.d-sm-none');
    }
}
