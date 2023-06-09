<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function getRegisterFields($overrides = [])
    {
        return array_merge([
            'name' => 'User Name',
            'email' => 'user@example.net',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }

    /** @test */
    public function new_user_can_register()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields());

        $this->seeStatusCode(200);

        $this->seeInDatabase('users', [
            'name' => 'User Name',
            'email' => 'user@example.net',
        ]);
    }

    /** @test */
    public function user_name_is_required()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields(['name' => '']));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [__('validation.required')],
            ],
        ]);
    }

    /** @test */
    public function user_name_maximum_is_255_characters()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields([
            'name' => str_repeat('John Thor ', 26),
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [__('validation.max.string', ['attribute' => 'name', 'max' => '255'])],
            ],
        ]);
    }

    /** @test */
    public function user_email_is_required()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields(['email' => '']));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [__('validation.required')],
            ],
        ]);
    }

    /** @test */
    public function user_email_must_be_a_valid_email()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields([
            'email' => 'username.example.net',
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [__('validation.email', ['attribute' => 'email'])],
            ],
        ]);
    }

    /** @test */
    public function user_email_maximum_is_255_characters()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields([
            'email' => str_repeat('username@example.net', 13),
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [
                    __('validation.email', ['attribute' => 'email']),
                    __('validation.max.string', ['attribute' => 'email', 'max' => '255']),
                ],
            ],
        ]);
    }

    /** @test */
    public function user_email_must_be_unique_on_users_table()
    {
        $user = $this->createUser(['email' => 'emailsama@example.net']);

        $this->postJson(route('api.register'), $this->getRegisterFields([
            'email' => 'emailsama@example.net',
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [
                    __('validation.unique', ['attribute' => 'email']),
                ],
            ],
        ]);
    }

    /** @test */
    public function user_password_is_required()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields(['password' => '']));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => [
                    __('validation.required'),
                ],
            ],
        ]);
    }

    /** @test */
    public function user_password_minimum_is_6_characters()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields([
            'password' => 'ecret',
            'password_confirmation' => 'ecret',
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => [
                    __('validation.min.string', ['attribute' => 'password', 'min' => '6']),
                ],
            ],
        ]);
    }

    /** @test */
    public function user_password_must_be_same_with_password_confirmation_field()
    {
        $this->postJson(route('api.register'), $this->getRegisterFields([
            'password' => 'secret',
            'password_confirmation' => 'escret',
        ]));

        $this->seeStatusCode(422);
        $this->seeJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => [
                    __('validation.confirmed', ['attribute' => 'password']),
                ],
            ],
        ]);
    }
}
