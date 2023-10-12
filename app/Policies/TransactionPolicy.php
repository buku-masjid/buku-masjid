<?php

namespace App\Policies;

use App\Transaction;
use App\User;

class TransactionPolicy
{
    public function create(User $user, Transaction $transaction): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return true;
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }
}
