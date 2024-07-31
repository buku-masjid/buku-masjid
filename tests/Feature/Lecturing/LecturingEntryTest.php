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
    public function user_can_duplicate_a_lecturing()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['date' => '2023-04-05']);
        $this->visitRoute('lecturings.show', $lecturing);
        $this->seeElement('a', ['id' => 'duplicate_lecturing-'.$lecturing->id]);

        $this->click('duplicate_lecturing-'.$lecturing->id);
        $this->seeRouteIs('lecturings.create', ['original_lecturing_id' => $lecturing->id]);

        $this->seeElement('input', ['type' => 'text', 'name' => 'date', 'value' => today()->format('Y-m-d')]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'start_time', 'value' => $lecturing->start_time]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'time_text', 'value' => $lecturing->time_text]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'lecturer_name', 'value' => $lecturing->lecturer_name]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'imam_name', 'value' => $lecturing->imam_name]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'muadzin_name', 'value' => $lecturing->muadzin_name]);
        $this->seeElement('input', ['type' => 'text', 'name' => 'title', 'value' => $lecturing->title]);
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
    public function validate_lecturing_date_and_start_time_is_unique()
    {
        factory(Lecturing::class)->create([
            'date' => '2023-05-01',
            'start_time' => '05:50',
            'time_text' => 'BA\'DA SUBUH',
        ]);
        $this->loginAsUser();

        $this->post(route('lecturings.store'), $this->getCreateFields([
            'date' => '2023-05-01',
            'start_time' => '05:50',
            'time_text' => 'BA\'DA SUBUH',
        ]));
        $this->assertSessionHasErrors('time_text');
    }

    /** @test */
    public function validate_lecturing_date_and_time_text_is_unique()
    {
        factory(Lecturing::class)->create([
            'date' => '2023-05-01',
            'time_text' => 'BA\'DA SUBUH',
        ]);
        $this->loginAsUser();

        $this->post(route('lecturings.store'), $this->getCreateFields([
            'date' => '2023-05-01',
            'time_text' => 'BA\'DA SUBUH',
        ]));
        $this->assertSessionHasErrors('time_text');
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
