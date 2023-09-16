<?php

namespace Tests\Feature\LecturingSchedule;

use App\Http\Livewire\TodayLecturerCard;
use App\Models\LecturingSchedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TodayCardLecturingScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_lecturing_today_card()
    {
        $user = factory(User::class)->create(['name' => 'Nama Member', 'email' => 'email@mail.com']);
        $this->loginAsUser();
        $this->visit('/');
        Livewire::test(TodayLecturerCard::class)
            ->assertCount('lecturingSchedules', 0)
            ->assertSee(__('lecturing_schedule.today_empty'));
    }

    /** @test */
    public function user_can_see_lecturing_today_card()
    {
        $user = factory(User::class)->create(['name' => 'Nama Member', 'email' => 'email@mail.com']);
        $this->loginAsUser();
        factory(LecturingSchedule::class)->create();
        $this->visit('/');
        Livewire::test(TodayLecturerCard::class)
            ->assertSeeHtml('<h3 class="card-title flex-grow-1">' .
                __('lecturing_schedule.lecturing_schedule') . ' ' .
                __('time.today') . '</h3>');
    }

    /** @test */
    public function user_can_see_carousel_lecturing_today_card()
    {
        $user = factory(User::class)->create(['name' => 'Nama Member', 'email' => 'email@mail.com']);
        $this->loginAsUser();
        factory(LecturingSchedule::class, 3)->create();
        $this->visit('/');
        Livewire::test(TodayLecturerCard::class)
            ->assertCount('lecturingSchedules', 3)
            ->assertSeeHtml('<h3 class="card-title flex-grow-1">' .
                __('lecturing_schedule.lecturing_schedule') . ' ' .
                __('time.today') . '</h3>');
    }

    /** @test */
    public function user_can_see_firday_lecturing_today_card()
    {
        Carbon::setTestNow("15-09-2023");
        $user = factory(User::class)->create(
            ['name' => 'Nama Member', 'email' => 'email@mail.com']
        );
        $this->loginAsUser();
        factory(LecturingSchedule::class)->create([
            'audience_code' => LecturingSchedule::AUDIENCE_FRIDAY
        ]);
        $this->visit('/');
        Livewire::test(TodayLecturerCard::class)
            ->assertCount('lecturingSchedules', 1)
            ->assertSeeHtml('<h3 class="card-title flex-grow-1">' .
                __('lecturing_schedule.friday_lecturing_schedule') . ' ' .
                __('time.today') . '</h3>');
    }
}
