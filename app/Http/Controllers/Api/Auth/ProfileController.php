<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;

class ProfileController extends Controller
{
    /**
     * Show user profile data.
     *
     * @return \App\Http\Resources\User
     */
    protected function show()
    {
        return new UserResource(auth()->user());
    }
}
