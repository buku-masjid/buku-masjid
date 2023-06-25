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
        return true;
    }

    public function view(User $user, BankAccount $bankAccount)
    {
        return $user->id == $bankAccount->creator_id;
    }

    public function update(User $user, BankAccount $bankAccount)
    {
        return $user->id == $bankAccount->creator_id;
    }

    public function delete(User $user, BankAccount $bankAccount)
    {
        return $user->id == $bankAccount->creator_id;
    }
}
