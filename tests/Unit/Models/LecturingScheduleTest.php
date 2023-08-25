<?php

namespace Tests\Unit\Models;

use App\User;
use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_lecturing_schedule_has_title_link_attribute()
    {
        $lecturingSchedule = LecturingSchedule::factory()->create();

        $title = __('app.show_detail_title', [
            'title' => $lecturingSchedule->title, 'type' => __('lecturing_schedule.lecturing_schedule'),
        ]);
        $link = '<a href="'.route('lecturing_schedules.show', $lecturingSchedule).'"';
        $link .= ' title="'.$title.'">';
        $link .= $lecturingSchedule->title;
        $link .= '</a>';

        $this->assertEquals($link, $lecturingSchedule->title_link);
    }

    /** @test */
    public function a_lecturing_schedule_has_belongs_to_creator_relation()
    {
        $lecturingSchedule = LecturingSchedule::factory()->make();

        $this->assertInstanceOf(User::class, $lecturingSchedule->creator);
        $this->assertEquals($lecturingSchedule->creator_id, $lecturingSchedule->creator->id);
    }
}
