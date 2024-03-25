<?php

namespace Tests\Feature\Livewire\PublicHome;

use App\Http\Livewire\PublicHome\DailyLecturings;
use App\Models\Lecturing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DailyLecturingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_lecturing_daily_card()
    {
        Livewire::test(DailyLecturings::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertCount('lecturings', 0)
            ->assertSee(__('lecturing.empty'));
    }

    /** @test */
    public function user_can_see_lecturing_daily_card()
    {
        factory(Lecturing::class)->create();
        Livewire::test(DailyLecturings::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertSee(__('lecturing.public_schedule').' '.__('time.today'));
    }

    /** @test */
    public function user_can_see_friday_lecturing_daily_card()
    {
        Carbon::setTestNow('2023-09-16');
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
        ]);
        factory(Lecturing::class)->create();
        Livewire::test(DailyLecturings::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertCount('lecturings', 2)
            ->assertSee(__('lecturing.friday_lecturer_name'))
            ->assertSee(__('lecturing.imam_name'))
            ->assertSee(__('lecturing.muadzin_name'))
            ->assertSee($lecturing->lecturer_name)
            ->assertSee($lecturing->imam_name)
            ->assertSee($lecturing->muadzin_name)
            ->assertSee(__('lecturing.audience_'.Lecturing::AUDIENCE_FRIDAY));
        Carbon::setTestNow();
    }
}
