<?php

namespace App\Policies;

use App\User;

class UserPolicy
{
    public function viewAny(User $authUser, User $user)
    {
        return $authUser->role_id == User::ROLE_ADMIN;
    }

    public function view(User $authUser, User $user)
    {
        return $authUser->role_id == User::ROLE_ADMIN;
    }

    public function create(User $authUser, User $user)
    {
        return $authUser->role_id == User::ROLE_ADMIN;
    }

    public function update(User $authUser, User $user)
    {
        return $authUser->role_id == User::ROLE_ADMIN;
    }

    public function delete(User $authUser, User $user)
    {
        return $authUser->role_id == User::ROLE_ADMIN;
    }
}
