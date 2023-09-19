<?php

namespace Tests\Feature\Liveware\PublicHome;

use App\Http\Livewire\PublicHome\TodayLecturingSchedules;
use App\Models\LecturingSchedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TodayLecturingSchedulesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_lecturing_today_card()
    {
        $this->visit('/');
        Livewire::test(TodayLecturingSchedules::class)
            ->assertCount('lecturingSchedules', 0)
            ->assertSee(__('lecturing_schedule.today_empty'));
    }

    /** @test */
    public function user_can_see_lecturing_today_card()
    {
        factory(LecturingSchedule::class)->create();
        $this->visit('/');
        Livewire::test(TodayLecturingSchedules::class)
            ->assertSee(__('lecturing_schedule.public_schedule') . ' ' . __('time.today'));
    }

    /** @test */
    public function user_can_see_friday_lecturing_today_card()
    {
        Carbon::setTestNow("16-09-2023");
        factory(User::class)->create(
            ['name' => 'Nama Member', 'email' => 'email@mail.com']
        );
        $this->loginAsUser();
        factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_FRIDAY
        ]);
        factory(LecturingSchedule::class)->create();
        $this->visit('/');
        Livewire::test(TodayLecturingSchedules::class)
            ->assertCount('lecturingSchedules', 2)
            ->assertSee(__('lecturing_schedule.friday_lecturer_name'))
            ->assertSee(__('lecturing_schedule.audience_' . LecturingSchedule::AUDIENCE_FRIDAY));
    }
}
