<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class GrantApiAccess
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return $next($request);
        }

        $user = Auth::guard($guard)->user();
        if ($user->access_token) {
            $user->withAccessToken(Crypt::decryptString($user->access_token));

            return $next($request);
        }

        return $this->redirectToLoginPage($request, $guard);
    }

    private function redirectToLoginPage($request, $guard = null)
    {
        Auth::guard($guard)->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        flash(__('auth.api_token_expired'), 'error');

        return redirect()->route('login');
    }
}
