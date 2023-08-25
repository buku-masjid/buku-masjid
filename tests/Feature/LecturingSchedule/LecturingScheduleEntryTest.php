<?php

namespace Tests\Feature\LecturingSchedule;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingScheduleEntryTest extends TestCase
{
    use RefreshDatabase;

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'title' => 'LecturingSchedule 1 title',
            'description' => 'LecturingSchedule 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_lecturing_schedule()
    {
        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.index');

        $this->click(__('lecturing_schedule.create'));
        $this->seeRouteIs('lecturing_schedules.create');

        $this->submitForm(__('app.create'), $this->getCreateFields());

        $this->seeRouteIs('lecturing_schedules.show', LecturingSchedule::first());

        $this->seeInDatabase('lecturing_schedules', $this->getCreateFields());
    }

    /** @test */
    public function validate_lecturing_schedule_title_is_required()
    {
        $this->loginAsUser();

        // title empty
        $this->post(route('lecturing_schedules.store'), $this->getCreateFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_schedule_title_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // title 70 characters
        $this->post(route('lecturing_schedules.store'), $this->getCreateFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_schedule_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('lecturing_schedules.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }
}
