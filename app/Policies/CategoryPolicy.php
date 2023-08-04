<?php

namespace App\Policies;

use App\Models\Category;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Category $category)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Category $category)
    {
        return true;
    }

    public function update(User $user, Category $category)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function delete(User $user, Category $category)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }
}
