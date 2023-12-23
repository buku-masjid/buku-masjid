<?php

namespace Tests\Unit\Policies;

use App\Models\Lecturing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_lecturing_list()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('view-any', new Lecturing));
        $this->assertTrue($chairman->can('view-any', new Lecturing));
        $this->assertTrue($secretary->can('view-any', new Lecturing));
        $this->assertTrue($finance->can('view-any', new Lecturing));
    }

    /** @test */
    public function user_can_create_lecturing()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new Lecturing));
        $this->assertTrue($chairman->can('create', new Lecturing));
        $this->assertTrue($secretary->can('create', new Lecturing));
        $this->assertFalse($finance->can('create', new Lecturing));
    }

    /** @test */
    public function user_can_view_lecturing()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturing = factory(Lecturing::class)->create();

        $this->assertTrue($admin->can('view', $lecturing));
        $this->assertTrue($chairman->can('view', $lecturing));
        $this->assertTrue($secretary->can('view', $lecturing));
        $this->assertTrue($finance->can('view', $lecturing));
    }

    /** @test */
    public function user_can_update_lecturing()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturing = factory(Lecturing::class)->create();

        $this->assertTrue($admin->can('update', $lecturing));
        $this->assertTrue($chairman->can('update', $lecturing));
        $this->assertTrue($secretary->can('update', $lecturing));
        $this->assertFalse($finance->can('update', $lecturing));
    }

    /** @test */
    public function user_can_delete_lecturing()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $lecturing = factory(Lecturing::class)->create();

        $this->assertTrue($admin->can('delete', $lecturing));
        $this->assertTrue($chairman->can('delete', $lecturing));
        $this->assertTrue($secretary->can('delete', $lecturing));
        $this->assertFalse($finance->can('delete', $lecturing));
    }
}
