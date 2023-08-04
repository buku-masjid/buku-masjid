<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use HandlesAuthorization;

    public function create(User $user, BankAccount $bankAccount)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, BankAccount $bankAccount)
    {
        return true;
    }

    public function update(User $user, BankAccount $bankAccount)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function delete(User $user, BankAccount $bankAccount)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }
}
