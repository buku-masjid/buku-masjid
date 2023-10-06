<?php

namespace Tests\Unit\Policies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomPermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_manage_database_backup_files()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('manage_database_backup'));
        $this->assertFalse($chairman->can('manage_database_backup'));
        $this->assertFalse($secretary->can('manage_database_backup'));
        $this->assertFalse($finance->can('manage_database_backup'));
    }
}
