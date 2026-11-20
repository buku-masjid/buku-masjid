<?php

namespace Tests\Feature\Livewire\PublicDisplay;

use App\Http\Livewire\PublicDisplay\LecturingSchedules;
use App\Models\Lecturing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LecturingSchedulesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_no_slide_when_there_is_no_upcoming_lecturing()
    {
        Livewire::test(LecturingSchedules::class, ['theme' => 'default'])
            ->assertCount('lecturings', 0)
            ->assertDontSee(__('lecturing.upcoming'));
    }

    /** @test */
    public function it_shows_upcoming_lecturings_within_the_configured_days()
    {
        config(['public_display.lecturing_schedule_days' => 7]);
        $todayLecturing = factory(Lecturing::class)->create(['title' => 'Kajian Hari Ini']);
        $nextWeekLecturing = factory(Lecturing::class)->create([
            'date' => today()->addDays(6)->format('Y-m-d'),
            'title' => 'Kajian Pekan Ini',
        ]);
        factory(Lecturing::class)->create([
            'date' => today()->subDay()->format('Y-m-d'),
            'title' => 'Kajian Kemarin',
        ]);
        factory(Lecturing::class)->create([
            'date' => today()->addDays(7)->format('Y-m-d'),
            'title' => 'Kajian Pekan Depan',
        ]);

        Livewire::test(LecturingSchedules::class, ['theme' => 'default'])
            ->assertCount('lecturings', 2)
            ->assertSee(__('lecturing.upcoming'))
            ->assertSee($todayLecturing->title)
            ->assertSee($todayLecturing->lecturer_name)
            ->assertSee($nextWeekLecturing->title)
            ->assertDontSee('Kajian Kemarin')
            ->assertDontSee('Kajian Pekan Depan');
    }

    /** @test */
    public function it_limits_the_number_of_lecturings_shown()
    {
        config(['public_display.lecturing_schedule_max_items' => 2]);
        factory(Lecturing::class, 3)->create();

        Livewire::test(LecturingSchedules::class, ['theme' => 'default'])
            ->assertCount('lecturings', 2);
    }

    /** @test */
    public function it_shows_khatib_label_for_friday_lecturing()
    {
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
            'start_time' => '12:10',
        ]);

        Livewire::test(LecturingSchedules::class, ['theme' => 'default'])
            ->assertSee(__('lecturing.friday_lecturer_name'))
            ->assertSee(__('lecturing.adzan_time').' 12:10')
            ->assertSee($lecturing->lecturer_name);
    }
}
