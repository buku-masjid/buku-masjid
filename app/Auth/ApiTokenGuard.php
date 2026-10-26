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
        if (!$this->isValidToken($credentials[$this->inputKey] ?? null)) {
            return false;
        }

        return parent::validate($credentials);
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
