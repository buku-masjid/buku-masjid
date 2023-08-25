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
        $this->see($lecturingSchedule->lecturer);
    }

    /** @test */
    public function user_can_see_lecturing_schedule_detail_in_lecturing_schedule_show_page()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.show', $lecturingSchedule);
        $this->see($lecturingSchedule->lecturer);
    }
}
