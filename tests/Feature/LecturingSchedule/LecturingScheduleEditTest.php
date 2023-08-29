<?php

namespace Tests\Feature\LecturingSchedule;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingScheduleEditTest extends TestCase
{
    use RefreshDatabase;

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'date' => '2023-01-03',
            'start_time' => '06:00',
            'end_time' => '06:45',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => 'Ustadz Haikal',
            'title' => 'Lecturing title',
            'book_title' => 'Book title',
            'book_writer' => 'Book writer',
            'book_link' => 'https://drive.google.com',
            'video_link' => 'https://youtube.com',
            'audio_link' => 'https://audio.com',
            'description' => 'Lecturing description',
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
        $this->seeText(__('lecturing_schedule.updated'));

        $this->seeInDatabase('lecturing_schedules', $this->getEditFields([
            'id' => $lecturingSchedule->id,
        ]));
    }

    /** @test */
    public function validate_lecturing_schedule_date_update_is_required()
    {
        $this->loginAsUser();
        $lecturing_schedule = factory(LecturingSchedule::class)->create(['date' => 'Testing 123']);

        // date empty
        $this->patch(route('lecturing_schedules.update', $lecturing_schedule), $this->getEditFields(['date' => '']));
        $this->assertSessionHasErrors('date');
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
        $this->seeRouteIs('lecturing_schedules.index');

        $this->seeText(__('lecturing_schedule.deleted'));

        $this->dontSeeInDatabase('lecturing_schedules', [
            'id' => $lecturingSchedule->id,
        ]);
    }

    /** @test */
    public function user_can_edit_a_friday_lecturing_schedule()
    {
        $this->loginAsUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create(['audience_code' => 'friday']);

        $this->visitRoute('lecturing_schedules.show', $lecturingSchedule);
        $this->click('edit-lecturing_schedule-'.$lecturingSchedule->id);
        $this->seeRouteIs('friday_lecturing_schedules.edit', $lecturingSchedule);
        $this->seeText(__('lecturing_schedule.edit_for_friday'));

        $this->submitForm(__('lecturing_schedule.update'), $this->getEditForFridayFields());

        $this->seeRouteIs('friday_lecturing_schedules.show', $lecturingSchedule);
        $this->seeText(__('lecturing_schedule.updated'));

        $this->seeInDatabase('lecturing_schedules', $this->getEditForFridayFields([
            'id' => $lecturingSchedule->id,
        ]));
    }

    private function getEditForFridayFields(): array
    {
        return [
            'date' => '2023-01-03',
            'start_time' => '06:00',
            'lecturer_name' => 'Ustadz Haikal',
            'title' => 'Lecturing title',
            'video_link' => 'https://youtube.com',
            'audio_link' => 'https://audio.com',
            'description' => 'Test description',
        ];
    }
}
