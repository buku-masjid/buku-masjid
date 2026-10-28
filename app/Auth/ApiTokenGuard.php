<?php

namespace App\Auth;

use Illuminate\Auth\TokenGuard;
use Illuminate\Http\Request;

class ApiTokenGuard extends TokenGuard
{
    use \Illuminate\Support\Traits\Macroable;

    private $token = null;

    public function user()
    {
        $user = parent::user();
        $token = $this->getTokenForRequest();

        if (is_null($user) || is_null($token) || $this->tokenMatchesStoredToken($user, $token)) {
            return $user;
        }

        return $this->user = null;
    }

    public function getTokenForRequest()
    {
        if (is_null($this->token)) {
            $token = parent::getTokenForRequest();
            $this->token = $this->isValidToken($token) ? $token : false;
        }

        return $this->token ?: null;
    }

    public function setRequest(Request $request)
    {
        $this->token = null;

        return parent::setRequest($request);
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

        return is_string($user->api_token) && hash_equals($user->api_token, $token);
    }

    private function isValidToken($token)
    {
        return is_string($token) && $token !== '';
    }
}
