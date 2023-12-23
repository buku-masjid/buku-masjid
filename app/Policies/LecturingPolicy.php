<?php

namespace App\Policies;

use App\Models\Lecturing;
use App\User;

class LecturingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lecturing $lecturing): bool
    {
        return true;
    }

    public function create(User $user, Lecturing $lecturing): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, Lecturing $lecturing): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, Lecturing $lecturing): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }
}
