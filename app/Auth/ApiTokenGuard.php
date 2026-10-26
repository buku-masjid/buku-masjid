<?php

namespace App\Auth;

use Illuminate\Auth\TokenGuard;

class ApiTokenGuard extends TokenGuard
{
    use \Illuminate\Support\Traits\Macroable;

    public function user()
    {
        $user = parent::user();

        if (is_null($user) || $this->tokenMatchesStoredToken($user, $this->getTokenForRequest())) {
            return $user;
        }

        return $this->user = null;
    }

    public function getTokenForRequest()
    {
        $token = parent::getTokenForRequest();

        return $this->isValidToken($token) ? $token : null;
    }

    public function validate(array $credentials = [])
    {
        $token = $credentials[$this->inputKey] ?? null;

        if (!$this->isValidToken($token)) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials([
            $this->storageKey => $this->hash ? hash('sha256', $token) : $token,
        ]);

        return $user && $this->tokenMatchesStoredToken($user, $token);
    }

    private function tokenMatchesStoredToken($user, $token)
    {
        if ($this->hash) {
            return true;
        }

        return is_string($token) && is_string($user->api_token) && hash_equals($user->api_token, $token);
    }

    private function isValidToken($token)
    {
        return is_string($token) && $token !== '';
    }
}
