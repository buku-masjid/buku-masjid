<?php

namespace Tests\Unit\Policies;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingSchedulePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_lecturing_schedule()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new LecturingSchedule));
    }

    /** @test */
    public function user_can_view_lecturing_schedule()
    {
        $user = $this->createUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create();
        $this->assertTrue($user->can('view', $lecturingSchedule));
    }

    /** @test */
    public function user_can_update_lecturing_schedule()
    {
        $user = $this->createUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create();
        $this->assertTrue($user->can('update', $lecturingSchedule));
    }

    /** @test */
    public function user_can_delete_lecturing_schedule()
    {
        $user = $this->createUser();
        $lecturingSchedule = factory(LecturingSchedule::class)->create();
        $this->assertTrue($user->can('delete', $lecturingSchedule));
    }
}
