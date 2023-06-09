<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_user_can_register()
    {
        $this->visit(route('register'));

        $this->submitForm(__('auth.register'), [
            'name' => 'Nama Member',
            'email' => 'email@mail.com',
            'password' => 'password.111',
            'password_confirmation' => 'password.111',
        ]);

        $this->seePageIs(route('home'));

        $this->seeInDatabase('users', [
            'name' => 'Nama Member',
            'email' => 'email@mail.com',
        ]);
    }
}
