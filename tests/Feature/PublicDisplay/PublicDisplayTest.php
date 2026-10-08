<?php

namespace Tests\Feature\PublicDisplay;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDisplayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function visitor_can_see_public_display_page(): void
    {
        $this->visitRoute('public.display.index');

        $this->seeElement('div', ['id' => 'timeRemaining']);
        $this->seeElement('h1', ['data-time' => 'imsak']);

        $this->see(__('shalat_time.time_before_text'));
        $this->see(__('shalat_time.iqamah_interval_text'));
        $this->see(__('shalat_time.shalat_interval_text'));
        $this->see(__('shalat_time.friday_interval_text'));
    }
}
