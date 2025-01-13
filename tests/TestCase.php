<?php

namespace Tests;

use App\User;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';

    protected function loginAsUser($role = 'admin', $userDataOverrides = [])
    {
        $user = $this->createUser($role, $userDataOverrides);
        $this->actingAs($user);

        return $user;
    }

    protected function createUser($role = 'admin', $userDataOverrides = [])
    {
        if ($role == 'admin') {
            $userDataOverrides = array_merge($userDataOverrides, ['role_id' => User::ROLE_ADMIN]);
        }
        if ($role == 'chairman') {
            $userDataOverrides = array_merge($userDataOverrides, ['role_id' => User::ROLE_CHAIRMAN]);
        }
        if ($role == 'secretary') {
            $userDataOverrides = array_merge($userDataOverrides, ['role_id' => User::ROLE_SECRETARY]);
        }
        if ($role == 'finance') {
            $userDataOverrides = array_merge($userDataOverrides, ['role_id' => User::ROLE_FINANCE]);
        }

        return factory(User::class)->create($userDataOverrides);
    }

    protected function assertSessionMissingErrors($bindings = [], $format = null)
    {
        $bindings = (array) $bindings;
        $errors = $this->app['session.store']->get('errors');

        if (is_null($errors)) {
            PHPUnit::assertTrue(true);

            return $this;
        }

        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                PHPUnit::assertFalse($errors->has($value), "Session missing error: $value");
            } else {
                PHPUnit::assertNotContains($value, $errors->get($key, $format));
            }
        }
    }
}
