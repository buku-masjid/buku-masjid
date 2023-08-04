<?php

namespace App\Policies;

use App\Models\Book;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Book $book)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Book $book)
    {
        return true;
    }

    public function update(User $user, Book $book)
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function delete(User $user, Book $book)
    {
        if ($book->creator_id == null) {
            return false;
        }
        if (!in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])) {
            return false;
        }
        if (!in_array($user->role_id, [User::ROLE_ADMIN]) && $book->creator_id != $user->id) {
            return false;
        }

        return true;
    }
}
