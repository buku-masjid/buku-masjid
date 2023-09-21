<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show a profile user.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = auth()->user();

        return view('auth.profile.show', compact('user'));
    }

    /**
     * Edit a profile user.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = auth()->user();

        return view('auth.profile.edit', compact('user'));
    }

    /**
     * Update a profile user.
     *
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|max:60',
            'email' => 'required|max:255|unique:users,email,'.auth()->id(),
        ]);

        auth()->user()->update($userData);
        flash(__('user.profile_updated'), 'success');

        return redirect()->route('profile.show');
    }
}
