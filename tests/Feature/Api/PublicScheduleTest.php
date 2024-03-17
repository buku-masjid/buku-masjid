<?php

namespace Tests\Feature\Api;

use App\Models\Lecturing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_schedules_within_specified_date_range() 
    {
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);

        $unlistedLecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_PUBLIC,
            'date' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(7)->format('Y-m-d');

        $this->getJson(route('api.schedules.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));

        $this->seeJson([
            'lecturer_name' => $lecturing->lecturer_name,
            'imam_name' => $lecturing->imam_name,
            'muadzin_name' => $lecturing->muadzin_name,
        ]);

        $this->dontSeeJson([
            'lecturer_name' => $unlistedLecturing->lecturer_name,
            'imam_name' => $unlistedLecturing->imam_name,
            'muadzin_name' => $unlistedLecturing->muadzin_name,
        ]);
    }

    /** @test */
    public function can_get_schedules_without_specified_date_range() 
    {
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'date' => Carbon::now()->startOfMonth()->addDays(rand(0, 29)),
        ]);

        $this->getJson(route('api.schedules.index'));

        $this->seeJson([
            'lecturer_name' => $lecturing->lecturer_name,
            'imam_name' => $lecturing->imam_name,
            'muadzin_name' => $lecturing->muadzin_name,
        ]);
    }
}
