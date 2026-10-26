<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_via_api()
    {
        $user = $this->createUser('admin', ['password' => bcrypt('testing')]);
        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'testing',
        ]);

        $this->seeJson([
            'name' => $user->name,
            'email' => $user->email,
            'api_token' => $user->api_token,
        ]);
    }

    /** @test */
    public function user_cannot_login_via_api_when_the_user_is_inactive()
    {
        $user = $this->createUser('admin', [
            'password' => bcrypt('testing'),
            'is_active' => 0,
        ]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'testing',
        ]);

        $this->seeStatusCode(422);
        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => ['email' => [__('auth.user_inactive')]],
        ]);
        $this->assertArrayNotHasKey('api_token', $this->response->json());
    }

    /** @test */
    public function user_with_legacy_inactive_status_cannot_login_via_api()
    {
        $user = $this->createUser('admin', [
            'password' => bcrypt('testing'),
            'is_active' => 2,
        ]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'testing',
        ]);

        $this->seeStatusCode(422);
        $this->assertArrayNotHasKey('api_token', $this->response->json());
    }

    /** @test */
    public function invalid_user_api_login_returns_proper_message()
    {
        $this->postJson(route('api.login'), [
            'email' => '',
            'password' => '',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [__('validation.required')],
                'password' => [__('validation.required')],
            ],
        ]);

        $this->postJson(route('api.login'), [
            'email' => 'member@example.net',
            'password' => '',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => ['password' => [__('validation.required')]],
        ]);

        $this->postJson(route('api.login'), [
            'email' => 'member@example.net',
            'password' => 'testing',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => ['email' => [__('auth.failed')]],
        ]);
    }
}
