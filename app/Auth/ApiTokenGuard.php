<?php

namespace App\Auth;

use Illuminate\Auth\TokenGuard;

/**
 * The plain `token` guard does not use the Macroable trait, but this app
 * registers `activeBook()` / `activeBookId()` macros on the guard it uses.
 * This subclass adds Macroable so those macros work on the API guard.
 *
 * It also rejects any token that is not a non-empty string, so a `api_token`
 * input of type boolean, integer, array or null never reaches the user provider.
 */
class ApiTokenGuard extends TokenGuard
{
    use \Illuminate\Support\Traits\Macroable;

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        $user = parent::user();

        if (is_null($user) || $this->tokenMatchesStoredToken($user, $this->getTokenForRequest())) {
            return $user;
        }

        return $this->user = null;
    }

    /**
     * Get the token for the current request.
     *
     * @return string|null
     */
    public function getTokenForRequest()
    {
        $token = parent::getTokenForRequest();

        return $this->isValidToken($token) ? $token : null;
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     */
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

    /**
     * The stored token column uses a case-insensitive collation on MySQL/MariaDB,
     * so the provider can return a user whose token only matches case-insensitively
     * (or ignoring trailing spaces). Verify the exact token before trusting it.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  mixed  $token
     * @return bool
     */
    private function tokenMatchesStoredToken($user, $token)
    {
        if ($this->hash) {
            return true;
        }

        return is_string($token) && is_string($user->api_token) && hash_equals($user->api_token, $token);
    }

    /**
     * @param  mixed  $token
     * @return bool
     */
    private function isValidToken($token)
    {
        return is_string($token) && $token !== '';
    }
}
