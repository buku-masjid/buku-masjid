<?php

namespace Tests\Feature;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageLecturingScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_lecturing_schedule_list_in_lecturing_schedule_index_page()
    {
        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.index');
        $this->see($lecturingSchedule->title);
    }

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

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'title' => 'LecturingSchedule 1 title',
            'description' => 'LecturingSchedule 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_lecturing_schedule()
    {
        $this->loginAsUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create(['title' => 'Testing 123']);

        $this->visitRoute('lecturing_schedules.show', $lecturingSchedule);
        $this->click('edit-lecturing_schedule-'.$lecturingSchedule->id);
        $this->seeRouteIs('lecturing_schedules.edit', $lecturingSchedule);

        $this->submitForm(__('lecturing_schedule.update'), $this->getEditFields());

        $this->seeRouteIs('lecturing_schedules.show', $lecturingSchedule);

        $this->seeInDatabase('lecturing_schedules', $this->getEditFields([
            'id' => $lecturingSchedule->id,
        ]));
    }

    /** @test */
    public function validate_lecturing_schedule_title_update_is_required()
    {
        $this->loginAsUser();
        $lecturing_schedule = factory(LecturingSchedule::class)->create(['title' => 'Testing 123']);

        // title empty
        $this->patch(route('lecturing_schedules.update', $lecturing_schedule), $this->getEditFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_schedule_title_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $lecturing_schedule = factory(LecturingSchedule::class)->create(['title' => 'Testing 123']);

        // title 70 characters
        $this->patch(route('lecturing_schedules.update', $lecturing_schedule), $this->getEditFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_schedule_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $lecturing_schedule = factory(LecturingSchedule::class)->create(['title' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('lecturing_schedules.update', $lecturing_schedule), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_lecturing_schedule()
    {
        $this->loginAsUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create();
        factory(LecturingSchedule::class)->create();

        $this->visitRoute('lecturing_schedules.edit', $lecturingSchedule);
        $this->click('del-lecturing_schedule-'.$lecturingSchedule->id);
        $this->seeRouteIs('lecturing_schedules.edit', [$lecturingSchedule, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('lecturing_schedules', [
            'id' => $lecturingSchedule->id,
        ]);
    }
}
