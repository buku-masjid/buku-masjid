<?php

namespace Tests\Feature\Lecturing;

use App\Models\Lecturing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingEditTest extends TestCase
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
    public function user_can_edit_a_lecturing()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['title' => 'Testing 123']);

        $this->visitRoute('lecturings.show', $lecturing);
        $this->click('edit-lecturing-'.$lecturing->id);
        $this->seeRouteIs('lecturings.edit', $lecturing);

        $this->submitForm(__('app.save'), $this->getEditFields());

        $this->seeRouteIs('lecturings.show', $lecturing);
        $this->seeText(__('lecturing.updated'));

        $this->seeInDatabase('lecturings', $this->getEditFields([
            'id' => $lecturing->id,
        ]));
    }

    /** @test */
    public function validate_lecturing_date_update_is_required()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['date' => '2023-03-01']);

        // date empty
        $this->patch(route('lecturings.update', $lecturing), $this->getEditFields(['date' => '']));
        $this->assertSessionHasErrors('date');
    }

    /** @test */
    public function validate_lecturing_date_and_time_text_update_is_unique()
    {
        factory(Lecturing::class)->create([
            'date' => '2023-05-01',
            'time_text' => 'BA\'DA SUBUH',
        ]);
        $lecturing = factory(Lecturing::class)->create([
            'date' => '2023-03-01',
            'time_text' => 'BA\'DA ISYA',
        ]);
        $this->loginAsUser();

        $this->patch(route('lecturings.update', $lecturing), $this->getEditFields([
            'date' => '2023-05-01',
            'time_text' => 'BA\'DA SUBUH',
        ]));
        $this->assertSessionHasErrors('time_text');
    }

    /** @test */
    public function validate_lecturing_date_and_start_time_update_is_unique()
    {
        factory(Lecturing::class)->create([
            'date' => '2023-05-01',
            'start_time' => '05:50',
            'time_text' => 'BA\'DA SUBUH',
        ]);
        $lecturing = factory(Lecturing::class)->create([
            'date' => '2023-03-01',
            'start_time' => '20:10',
            'time_text' => 'BA\'DA ISYA',
        ]);
        $this->loginAsUser();

        $this->patch(route('lecturings.update', $lecturing), $this->getEditFields([
            'date' => '2023-05-01',
            'start_time' => '05:50',
            'time_text' => 'BA\'DA SUBUH',
        ]));
        $this->assertSessionHasErrors('start_time');
    }

    /** @test */
    public function validate_lecturing_title_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['title' => 'Testing 123']);

        // title 70 characters
        $this->patch(route('lecturings.update', $lecturing), $this->getEditFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_lecturing_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['title' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('lecturings.update', $lecturing), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_lecturing()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create();
        factory(Lecturing::class)->create();

        $this->visitRoute('lecturings.edit', $lecturing);
        $this->click('del-lecturing-'.$lecturing->id);
        $this->seeRouteIs('lecturings.edit', [$lecturing, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));
        $this->seeRouteIs('lecturings.index');

        $this->seeText(__('lecturing.deleted'));

        $this->dontSeeInDatabase('lecturings', [
            'id' => $lecturing->id,
        ]);
    }

    /** @test */
    public function user_can_edit_a_friday_lecturing()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['audience_code' => 'friday']);

        $this->visitRoute('lecturings.show', $lecturing);
        $this->click('edit-lecturing-'.$lecturing->id);
        $this->seeRouteIs('friday_lecturings.edit', $lecturing);
        $this->seeText(__('lecturing.edit_for_friday'));

        $this->submitForm(__('app.save'), $this->getEditForFridayFields());

        $this->seeRouteIs('friday_lecturings.show', $lecturing);
        $this->seeText(__('lecturing.updated'));

        $this->seeInDatabase('lecturings', $this->getEditForFridayFields([
            'id' => $lecturing->id,
        ]));
    }

    /** @test */
    public function bugfix_prevent_double_friday_lecturing_update_on_the_same_date()
    {
        $this->loginAsUser();
        factory(Lecturing::class)->create(['date' => '2023-01-06', 'audience_code' => 'friday']);
        $lecturing = factory(Lecturing::class)->create(['date' => '2023-01-13', 'audience_code' => 'friday']);

        $this->patch(route('friday_lecturings.update', $lecturing), $this->getEditForFridayFields(['date' => '2023-01-06']));
        $this->assertSessionHasErrors('date');
    }

    /** @test */
    public function prevent_selecting_other_day_for_friday_lecturing_update()
    {
        $this->loginAsUser();
        $lecturing = factory(Lecturing::class)->create(['date' => '2023-01-13', 'audience_code' => 'friday']);

        $this->patch(route('friday_lecturings.update', $lecturing), $this->getEditForFridayFields(['date' => '2023-01-12']));
        $this->assertSessionHasErrors('date');
    }

    private function getEditForFridayFields(array $overrides = []): array
    {
        return array_merge([
            'date' => '2023-01-06',
            'start_time' => '06:00',
            'lecturer_name' => 'Ustadz Abdul',
            'title' => 'Lecturing Title 2',
            'imam_name' => 'Ustadz Khalid',
            'muadzin_name' => 'Nanang',
            'video_link' => 'https://youtube.com/ceramah',
            'audio_link' => 'https://audio.com/ceramah',
            'description' => 'Test description 123',
        ], $overrides);
    }
}
