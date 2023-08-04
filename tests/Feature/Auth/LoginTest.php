<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_and_logout()
    {
        $user = factory(User::class)->create(['name' => 'Nama Member', 'email' => 'email@mail.com']);

        $this->visit(route('login'));

        $this->submitForm(__('auth.login'), [
            'email' => 'email@mail.com',
            'password' => 'secret',
        ]);

        $this->see(__('auth.welcome', ['name' => $user->name]));
        $this->seePageIs(route('home'));
        $this->seeIsAuthenticated();

        $this->press(__('auth.logout'));

        $this->seePageIs('/');
    }

    /** @test */
    public function member_invalid_login()
    {
        $this->visit(route('login'));

        $this->submitForm(__('auth.login'), [
            'email' => 'email@mail.com',
            'password' => 'member',
        ]);

        $this->seePageIs(route('login'));
        $this->dontSeeIsAuthenticated();
    }

    /** @test */
    public function user_cannot_login_if_they_are_in_inactive_status()
    {
        $user = factory(User::class)->create([
            'email' => 'email@mail.com',
            'is_active' => 0,
        ]);

        $this->visit(route('login'));

        $this->submitForm(__('auth.login'), [
            'email' => 'email@mail.com',
            'password' => 'secret',
        ]);

        $this->see(__('auth.user_inactive'));
        $this->seePageIs(route('login'));
        $this->dontSeeIsAuthenticated();
    }

    /** @test */
    public function unauthenticated_users_are_redirects_to_login_page()
    {
        $this->visit(route('home'));
        $this->seePageIs(route('login'));
    }
}
