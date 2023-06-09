<?php

namespace App\Policies;

use App\Loan;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the loan.
     *
     * @param  \App\User  $user
     * @param  \App\Loan  $loan
     * @return mixed
     */
    public function view(User $user, Loan $loan)
    {
        // Update $user authorization to view $loan here.
        return true;
    }

    /**
     * Determine whether the user can create loan.
     *
     * @param  \App\User  $user
     * @param  \App\Loan  $loan
     * @return mixed
     */
    public function create(User $user, Loan $loan)
    {
        // Update $user authorization to create $loan here.
        return true;
    }

    /**
     * Determine whether the user can update the loan.
     *
     * @param  \App\User  $user
     * @param  \App\Loan  $loan
     * @return mixed
     */
    public function update(User $user, Loan $loan)
    {
        // Update $user authorization to update $loan here.
        return true;
    }

    /**
     * Determine whether the user can delete the loan.
     *
     * @param  \App\User  $user
     * @param  \App\Loan  $loan
     * @return mixed
     */
    public function delete(User $user, Loan $loan)
    {
        // Update $user authorization to delete $loan here.
        return true;
    }
}
