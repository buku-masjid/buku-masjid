<?php

namespace Tests\Unit\Policies;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingSchedulePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_lecturing_schedule_list()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('view-any', new LecturingSchedule));
        $this->assertTrue($chairman->can('view-any', new LecturingSchedule));
        $this->assertTrue($secretary->can('view-any', new LecturingSchedule));
        $this->assertTrue($finance->can('view-any', new LecturingSchedule));
    }

    /** @test */
    public function user_can_create_lecturing_schedule()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new LecturingSchedule));
        $this->assertTrue($chairman->can('create', new LecturingSchedule));
        $this->assertTrue($secretary->can('create', new LecturingSchedule));
        $this->assertFalse($finance->can('create', new LecturingSchedule));
    }

    /** @test */
    public function user_can_view_lecturing_schedule()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->assertTrue($admin->can('view', $lecturingSchedule));
        $this->assertTrue($chairman->can('view', $lecturingSchedule));
        $this->assertTrue($secretary->can('view', $lecturingSchedule));
        $this->assertTrue($finance->can('view', $lecturingSchedule));
    }

    /** @test */
    public function user_can_update_lecturing_schedule()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->assertTrue($admin->can('update', $lecturingSchedule));
        $this->assertTrue($chairman->can('update', $lecturingSchedule));
        $this->assertTrue($secretary->can('update', $lecturingSchedule));
        $this->assertFalse($finance->can('update', $lecturingSchedule));
    }

    /** @test */
    public function user_can_delete_lecturing_schedule()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturingSchedule = factory(LecturingSchedule::class)->create();

        $this->assertTrue($admin->can('delete', $lecturingSchedule));
        $this->assertTrue($chairman->can('delete', $lecturingSchedule));
        $this->assertTrue($secretary->can('delete', $lecturingSchedule));
        $this->assertFalse($finance->can('delete', $lecturingSchedule));
    }
}
