<?php

namespace App\Policies;

use App\Models\Partner;
use App\User;

class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Partner $partner): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE, User::ROLE_FINANCE]);
    }

    public function update(User $user, Partner $partner): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE, User::ROLE_FINANCE]);
    }

    public function delete(User $user, Partner $partner): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE, User::ROLE_FINANCE]);
    }
}
