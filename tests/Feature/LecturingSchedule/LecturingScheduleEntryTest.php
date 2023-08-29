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
            'audience_code' => LecturingSchedule::AUDIENCE_PUBLIC,
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
    public function user_can_create_a_lecturing_schedule()
    {
        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.index');

        $this->click(__('lecturing_schedule.create'));
        $this->seeRouteIs('lecturing_schedules.create');

        $this->submitForm(__('app.create'), $this->getCreateFields());

        $this->seeRouteIs('lecturing_schedules.show', LecturingSchedule::first());
        $this->seeText(__('lecturing_schedule.created'));

        $this->seeInDatabase('lecturing_schedules', $this->getCreateFields());
    }

    /** @test */
    public function validate_lecturing_schedule_date_is_required()
    {
        $this->loginAsUser();

        // date empty
        $this->post(route('lecturing_schedules.store'), $this->getCreateFields(['date' => '']));
        $this->assertSessionHasErrors('date');
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

    /** @test */
    public function user_can_create_a_friday_lecturing_schedule()
    {
        $this->loginAsUser();
        $this->visitRoute('lecturing_schedules.index');

        $this->click(__('lecturing_schedule.create_for_friday'));
        $this->seeRouteIs('friday_lecturing_schedules.create');

        $this->submitForm(__('app.create'), $this->getCreateForFridayFields());

        $this->seeRouteIs('friday_lecturing_schedules.show', LecturingSchedule::first());
        $this->seeText(__('lecturing_schedule.created'));

        $this->seeInDatabase('lecturing_schedules', $this->getCreateForFridayFields());
    }

    private function getCreateForFridayFields(): array
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
