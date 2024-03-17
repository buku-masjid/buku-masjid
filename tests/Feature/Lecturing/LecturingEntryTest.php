<?php

namespace Tests\Feature\Lecturing;

use App\Models\Lecturing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingEntryTest extends TestCase
{
    use RefreshDatabase;

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'audience_code' => Lecturing::AUDIENCE_PUBLIC,
            'date' => '2023-01-03',
            'start_time' => '06:00',
            'end_time' => '06:45',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => 'Ustadz Haikal',
            'imam_name' => 'Ustadz Hafidz',
            'muadzin_name' => 'Ahmad',
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
    public function user_can_create_a_lecturing()
    {
        $this->loginAsUser();
        $this->visitRoute('lecturings.index');

        $this->click(__('lecturing.create'));
        $this->seeRouteIs('lecturings.create');

        $this->submitForm(__('app.create'), $this->getCreateFields());

        $this->seeRouteIs('lecturings.show', Lecturing::first());
        $this->seeText(__('lecturing.created'));

        $this->seeInDatabase('lecturings', $this->getCreateFields());
    }

    /** @test */
    public function validate_lecturing_date_is_required()
    {
        $this->loginAsUser();

        // date empty
        $this->post(route('lecturings.store'), $this->getCreateFields(['date' => '']));
        $this->assertSessionHasErrors('date');
    }

    /** @test */
    public function validate_lecturing_title_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // title 70 characters
        $this->post(route('lecturings.store'), $this->getCreateFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('lecturings.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_create_a_friday_lecturing()
    {
        $this->loginAsUser();
        $this->visitRoute('lecturings.index');

        $this->click(__('lecturing.create_for_friday'));
        $this->seeRouteIs('friday_lecturings.create');

        $this->submitForm(__('app.create'), $this->getCreateForFridayFields());

        $this->seeRouteIs('friday_lecturings.show', Lecturing::first());
        $this->seeText(__('lecturing.created'));

        $this->seeInDatabase('lecturings', $this->getCreateForFridayFields());
    }

    /** @test */
    public function bugfix_prevent_double_friday_lecturing_entries_on_the_same_date()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['date' => '2023-01-06', 'audience_code' => 'friday']);

        $this->post(route('friday_lecturings.store'), $this->getCreateForFridayFields(['date' => '2023-01-06']));
        $this->assertSessionHasErrors('date');
    }

    /** @test */
    public function prevent_selecting_other_day_for_friday_lecturing_entry()
    {
        $this->loginAsUser();

        $this->post(route('friday_lecturings.store'), $this->getCreateForFridayFields(['date' => '2023-01-05']));
        $this->assertSessionHasErrors('date');
    }

    private function getCreateForFridayFields(array $overrides = []): array
    {
        return array_merge([
            'date' => '2023-01-06',
            'start_time' => '06:00',
            'lecturer_name' => 'Ustadz Haikal',
            'title' => 'Lecturing title',
            'imam_name' => 'Ustadz Hamzah',
            'muadzin_name' => 'Ustadz Bilal',
            'video_link' => 'https://youtube.com',
            'audio_link' => 'https://audio.com',
            'description' => 'Test description',
        ], $overrides);
    }
}
