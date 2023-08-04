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
}
