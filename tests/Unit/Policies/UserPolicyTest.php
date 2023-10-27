<?php

namespace Tests\Unit\Policies;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_manage_users()
    {
        $adminUser = $this->createUser('admin');
        $financeUser = $this->createUser('finance');

        $this->assertTrue($adminUser->can('view-any', new User));
        $this->assertFalse($financeUser->can('view-any', new User));
    }

    /** @test */
    public function user_can_create_user()
    {
        $adminUser = $this->createUser('admin');
        $financeUser = $this->createUser('finance');

        $this->assertTrue($adminUser->can('create', new User));
        $this->assertFalse($financeUser->can('create', new User));
    }

    /** @test */
    public function user_can_view_user()
    {
        $adminUser = $this->createUser('admin');
        $financeUser = $this->createUser('finance');

        $user = $this->createUser('secretary');

        $this->assertTrue($adminUser->can('view', $user));
        $this->assertFalse($financeUser->can('view', $user));
    }

    /** @test */
    public function user_can_update_user()
    {
        $adminUser = $this->createUser('admin');
        $financeUser = $this->createUser('finance');

        $user = $this->createUser('secretary');

        $this->assertTrue($adminUser->can('update', $user));
        $this->assertFalse($financeUser->can('update', $user));
    }

    /** @test */
    public function user_can_delete_user()
    {
        $adminUser = $this->createUser('admin');
        $financeUser = $this->createUser('finance');

        $user = $this->createUser('secretary');

        $this->assertTrue($adminUser->can('delete', $user));
        $this->assertFalse($financeUser->can('delete', $user));
    }
}
