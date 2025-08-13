<?php

namespace Tests\Unit\Models;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_model_has_role_attribute()
    {
        $user = factory(User::class)->make(['role_id' => User::ROLE_ADMIN]);
        $this->assertEquals(__('user.role_admin'), $user->role);

        $user->role_id = User::ROLE_CHAIRMAN;
        $this->assertEquals(__('user.role_chairman'), $user->role);

        $user->role_id = User::ROLE_SECRETARY;
        $this->assertEquals(__('user.role_secretary'), $user->role);

        $user->role_id = User::ROLE_FINANCE;
        $this->assertEquals(__('user.role_finance'), $user->role);
    }

    /** @test */
    public function user_model_has_status_attribute()
    {
        $user = factory(User::class)->make(['is_active' => 1]);
        $this->assertEquals(__('app.active'), $user->status);

        $user = factory(User::class)->make(['is_active' => 0]);
        $this->assertEquals(__('app.inactive'), $user->status);
    }
}
