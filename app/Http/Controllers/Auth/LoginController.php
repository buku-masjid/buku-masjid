<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_active == 0) {
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            flash(trans('auth.user_inactive'), 'error');

            return redirect()->route('login');
        }
        if (is_null($user->access_token)) {
            $accessToken = $user->createToken('API Token')->accessToken;
            $user->access_token = Crypt::encryptString($accessToken);
            $user->save();
        }

        flash(trans('auth.welcome', ['name' => $user->name]));
    }
}
