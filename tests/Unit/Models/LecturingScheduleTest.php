<?php

namespace Tests\Unit\Models;

use App\Models\LecturingSchedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_lecturing_schedule_has_time_attribute()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->make([
            'start_time' => '19:00',
            'end_time' => '19:40',
            'time_text' => 'Ba\'da Magrib',
        ]);
        $this->assertEquals('19:00 s/d 19:40', $lecturingSchedule->time);

        $lecturingSchedule = factory(LecturingSchedule::class)->make([
            'start_time' => '19:00',
            'end_time' => null,
            'time_text' => 'Ba\'da Magrib',
        ]);
        $this->assertEquals('19:00 s/d selesai', $lecturingSchedule->time);

        $lecturingSchedule = factory(LecturingSchedule::class)->make([
            'start_time' => '19:00',
            'end_time' => null,
            'time_text' => null,
        ]);
        $this->assertEquals('19:00 s/d selesai', $lecturingSchedule->time);

        $lecturingSchedule = factory(LecturingSchedule::class)->make([
            'start_time' => '19:00',
            'end_time' => '19:40',
            'time_text' => null,
        ]);
        $this->assertEquals('19:00 s/d 19:40', $lecturingSchedule->time);
    }

    /** @test */
    public function lecturing_schedule_model_has_audience_attribute()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->make([
            'audience_code' => LecturingSchedule::AUDIENCE_PUBLIC,
        ]);
        $this->assertEquals(__('lecturing_schedule.audience_public'), $lecturingSchedule->audience);

        $lecturingSchedule->audience_code = LecturingSchedule::AUDIENCE_MUSLIMAH;
        $this->assertEquals(__('lecturing_schedule.audience_muslimah'), $lecturingSchedule->audience);

        $lecturingSchedule->audience_code = LecturingSchedule::AUDIENCE_FRIDAY;
        $this->assertEquals(__('lecturing_schedule.audience_friday'), $lecturingSchedule->audience);
    }

    /** @test */
    public function lecturing_schedule_model_has_day_name_attribute()
    {
        $date = '2017-01-31';
        $lecturingSchedule = factory(LecturingSchedule::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('dddd'), $lecturingSchedule->day_name);

        $lecturingSchedule = factory(LecturingSchedule::class)->make(['date' => null]);
        $this->assertEquals(null, $lecturingSchedule->day_name);
    }

    /** @test */
    public function a_lecturing_schedule_has_belongs_to_creator_relation()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->make();

        $this->assertInstanceOf(User::class, $lecturingSchedule->creator);
        $this->assertEquals($lecturingSchedule->creator_id, $lecturingSchedule->creator->id);
    }

    /** @test */
    public function a_lecturing_schedule_has_year_month_and_date_only_attribute()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->make(['date' => '2017-01-31']);

        $this->assertEquals('2017', $lecturingSchedule->year);
        $this->assertEquals('01', $lecturingSchedule->month);
        $this->assertEquals(Carbon::parse('2017-01-31')->isoFormat('MMM'), $lecturingSchedule->month_name);
        $this->assertEquals('31', $lecturingSchedule->date_only);
    }

    /** @test */
    public function lecturing_schedule_model_has_full_date_attribute()
    {
        $date = '2017-01-31';
        $lecturingSchedule = factory(LecturingSchedule::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('D MMMM Y'), $lecturingSchedule->full_date);
    }
}
