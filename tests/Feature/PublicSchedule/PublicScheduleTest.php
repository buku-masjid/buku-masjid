<?php

namespace Tests\Feature\PublicSchedule;

use App\Models\Lecturing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_friday_lecturing_today_in_public_schedule(): void
    {
        Carbon::setTestNow('2023-09-16');
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => '2023-09-16',
        ]);

        $this->visitRoute('public_schedules.today');
        $this->seeRouteIs('public_schedules.today');
        $this->seeText(__('lecturing.friday_lecturer_name'));
        $this->seeText($lecturing->lecturer_name);
        $this->seeText(__('lecturing.imam_name'));
        $this->seeText($lecturing->imam_name);
        $this->seeText(__('lecturing.muadzin_name'));
        $this->seeText($lecturing->muadzin_name);
        Carbon::setTestNow();
    }

    /** @test */
    public function user_can_see_friday_lecturing_tomorrow_in_public_schedule(): void
    {
        Carbon::setTestNow('2023-09-15');
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => '2023-09-16',
        ]);

        $this->visitRoute('public_schedules.tomorrow');
        $this->seeRouteIs('public_schedules.tomorrow');
        $this->seeText(__('lecturing.friday_lecturer_name'));
        $this->seeText($lecturing->lecturer_name);
        $this->seeText(__('lecturing.imam_name'));
        $this->seeText($lecturing->imam_name);
        $this->seeText(__('lecturing.muadzin_name'));
        $this->seeText($lecturing->muadzin_name);
        Carbon::setTestNow();
    }

    /** @test */
    public function user_can_see_friday_lecturing_this_week_in_public_schedule(): void
    {
        Carbon::setTestNow('2023-09-15');
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => '2023-09-16',
        ]);

        $this->visitRoute('public_schedules.this_week');
        $this->seeRouteIs('public_schedules.this_week');
        $this->seeText(__('lecturing.friday_lecturer_name'));
        $this->seeText($lecturing->lecturer_name);
        $this->seeText(__('lecturing.imam_name'));
        $this->seeText($lecturing->imam_name);
        $this->seeText(__('lecturing.muadzin_name'));
        $this->seeText($lecturing->muadzin_name);
        Carbon::setTestNow();
    }

    /** @test */
    public function user_can_see_friday_lecturing_next_week_in_public_schedule(): void
    {
        Carbon::setTestNow('2023-09-08');
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => '2023-09-16',
        ]);

        $this->visitRoute('public_schedules.next_week');
        $this->seeRouteIs('public_schedules.next_week');
        $this->seeText(__('lecturing.friday_lecturer_name'));
        $this->seeText($lecturing->lecturer_name);
        $this->seeText(__('lecturing.imam_name'));
        $this->seeText($lecturing->imam_name);
        $this->seeText(__('lecturing.muadzin_name'));
        $this->seeText($lecturing->muadzin_name);
        Carbon::setTestNow();
    }
}
