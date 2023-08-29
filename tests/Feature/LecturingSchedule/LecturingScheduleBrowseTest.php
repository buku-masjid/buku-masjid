<?php

namespace Tests\Feature\LecturingSchedule;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingScheduleBrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_lecturing_schedule_list_in_lecturing_schedule_index_page()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.index');
        $this->see($lecturingSchedule->lecturer_name);
    }

    /** @test */
    public function user_can_see_lecturing_schedule_detail_in_lecturing_schedule_show_page()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_PUBLIC,
        ]);

        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.show', $lecturingSchedule);
        $this->see($lecturingSchedule->lecturer_name);
    }

    /** @test */
    public function user_will_be_redirected_to_lecturing_schedule_detail_based_on_the_audience_code()
    {
        $publicLecturingSchedule = factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_PUBLIC,
        ]);

        $this->loginAsUser();
        $this->visitRoute('friday_lecturing_schedules.show', $publicLecturingSchedule);
        $this->seeRouteIs('lecturing_schedules.show', $publicLecturingSchedule);

        $fridayLecturingSchedule = factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_FRIDAY,
        ]);

        $this->visitRoute('lecturing_schedules.show', $fridayLecturingSchedule);
        $this->seeRouteIs('friday_lecturing_schedules.show', $fridayLecturingSchedule);
    }

    /** @test */
    public function user_can_see_lecturing_schedule_detail_in_friday_lecturing_schedule_show_page()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_FRIDAY,
        ]);

        $this->loginAsUser();
        $this->visitRoute('friday_lecturing_schedules.show', $lecturingSchedule);
        $this->seeText(__('lecturing_schedule.friday_lecturer_name'));
        $this->seeText($lecturingSchedule->lecturer_name);
    }
}
