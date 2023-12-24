<?php

namespace Tests\Unit\Models;

use App\Models\Lecturing;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_lecturing_has_time_attribute()
    {
        $lecturing = factory(Lecturing::class)->make([
            'start_time' => '19:00',
            'end_time' => '19:40',
            'time_text' => 'Ba\'da Magrib',
        ]);
        $this->assertEquals('19:00 s/d 19:40', $lecturing->time);

        $lecturing = factory(Lecturing::class)->make([
            'start_time' => '19:00',
            'end_time' => null,
            'time_text' => 'Ba\'da Magrib',
        ]);
        $this->assertEquals('19:00 s/d selesai', $lecturing->time);

        $lecturing = factory(Lecturing::class)->make([
            'start_time' => '19:00',
            'end_time' => null,
            'time_text' => null,
        ]);
        $this->assertEquals('19:00 s/d selesai', $lecturing->time);

        $lecturing = factory(Lecturing::class)->make([
            'start_time' => '19:00',
            'end_time' => '19:40',
            'time_text' => null,
        ]);
        $this->assertEquals('19:00 s/d 19:40', $lecturing->time);
    }

    /** @test */
    public function lecturing_model_has_audience_attribute()
    {
        $lecturing = factory(Lecturing::class)->make([
            'audience_code' => Lecturing::AUDIENCE_PUBLIC,
        ]);
        $this->assertEquals(__('lecturing.audience_public'), $lecturing->audience);

        $lecturing->audience_code = Lecturing::AUDIENCE_MUSLIMAH;
        $this->assertEquals(__('lecturing.audience_muslimah'), $lecturing->audience);

        $lecturing->audience_code = Lecturing::AUDIENCE_FRIDAY;
        $this->assertEquals(__('lecturing.audience_friday'), $lecturing->audience);
    }

    /** @test */
    public function lecturing_model_has_day_name_attribute()
    {
        $date = '2017-01-31';
        $lecturing = factory(Lecturing::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('dddd'), $lecturing->day_name);

        $lecturing = factory(Lecturing::class)->make(['date' => null]);
        $this->assertEquals(null, $lecturing->day_name);
    }

    /** @test */
    public function a_lecturing_has_belongs_to_creator_relation()
    {
        $lecturing = factory(Lecturing::class)->make();

        $this->assertInstanceOf(User::class, $lecturing->creator);
        $this->assertEquals($lecturing->creator_id, $lecturing->creator->id);
    }

    /** @test */
    public function a_lecturing_has_year_month_and_date_only_attribute()
    {
        $lecturing = factory(Lecturing::class)->make(['date' => '2017-01-31']);

        $this->assertEquals('2017', $lecturing->year);
        $this->assertEquals('01', $lecturing->month);
        $this->assertEquals(Carbon::parse('2017-01-31')->isoFormat('MMM'), $lecturing->month_name);
        $this->assertEquals('31', $lecturing->date_only);
    }

    /** @test */
    public function lecturing_model_has_full_date_attribute()
    {
        $date = '2017-01-31';
        $lecturing = factory(Lecturing::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('D MMMM Y'), $lecturing->full_date);
    }
}
