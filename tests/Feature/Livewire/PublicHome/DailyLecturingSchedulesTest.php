<?php

namespace Tests\Feature\Liveware\PublicHome;

use App\Http\Livewire\PublicHome\DailyLecturingSchedules;
use App\Models\LecturingSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DailyLecturingSchedulesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_lecturing_daily_card()
    {
        Livewire::test(DailyLecturingSchedules::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertCount('lecturingSchedules', 0)
            ->assertSee(__('lecturing_schedule.empty'));
    }

    /** @test */
    public function user_can_see_lecturing_daily_card()
    {
        factory(LecturingSchedule::class)->create();
        Livewire::test(DailyLecturingSchedules::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertSee(__('lecturing_schedule.public_schedule').' '.__('time.today'));
    }

    /** @test */
    public function user_can_see_friday_lecturing_daily_card()
    {
        Carbon::setTestNow('2023-09-16');
        factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_FRIDAY,
        ]);
        factory(LecturingSchedule::class)->create();
        Livewire::test(DailyLecturingSchedules::class, ['date' => today(), 'dayTitle' => 'today'])
            ->assertCount('lecturingSchedules', 2)
            ->assertSee(__('lecturing_schedule.friday_lecturer_name'))
            ->assertSee(__('lecturing_schedule.audience_'.LecturingSchedule::AUDIENCE_FRIDAY));
        Carbon::setTestNow();
    }
}
