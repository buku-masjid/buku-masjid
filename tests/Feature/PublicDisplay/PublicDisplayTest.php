<?php

namespace Tests\Feature\PublicDisplay;

use App\Models\Lecturing;
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

    /** @test */
    public function visitor_can_see_upcoming_lecturing_schedule_on_public_display_page(): void
    {
        $lecturing = factory(Lecturing::class)->create(['title' => 'Kajian Tafsir Pekanan']);

        $this->visitRoute('public.display.index');

        $this->see(__('lecturing.upcoming'));
        $this->see($lecturing->title);
        $this->see($lecturing->lecturer_name);
    }

    /** @test */
    public function lecturing_schedule_is_hidden_when_lecturings_feature_is_inactive(): void
    {
        config(['features.lecturings.is_active' => false]);
        factory(Lecturing::class)->create(['title' => 'Kajian Tafsir Pekanan']);

        $this->visitRoute('public.display.index');

        $this->dontSee(__('lecturing.upcoming'));
        $this->dontSee('Kajian Tafsir Pekanan');
    }
}
