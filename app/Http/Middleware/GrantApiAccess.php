<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class GrantApiAccess
{
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::guard($guard)->user();
        if ($user && $user->access_token) {
            $user->withAccessToken(Crypt::decryptString($user->access_token));
        }

        return $next($request);
    }
}
