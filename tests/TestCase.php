<?php

namespace Tests;

use App\User;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';

    protected function loginAsUser($userDataOverrides = [])
    {
        $user = $this->createUser($userDataOverrides);
        $this->actingAs($user);

        return $user;
    }

    protected function createUser($role = 'admin', $userDataOverrides = [])
    {
        if ($role == 'admin') {
            $userDataOverrides = array_merge($userDataOverrides, ['role_id' => User::ROLE_ADMIN]);
        }

        return factory(User::class)->create($userDataOverrides);
    }
}
