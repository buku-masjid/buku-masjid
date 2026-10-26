<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Send the response after the user was authenticated.
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = $request->user();

        if (!$user->isActive()) {
            $this->guard()->logout();

            throw ValidationException::withMessages([
                $this->username() => [__('auth.user_inactive')],
            ]);
        }

        if (empty($user->api_token)) {
            $user->api_token = Str::random(24);
            $user->save();
        }

        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->api_token = Str::random(24);
        $user->save();

        return response()->json([
            'message' => __('auth.logged_out'),
        ], 200);
    }
}
