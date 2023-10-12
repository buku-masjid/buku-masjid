<?php

namespace App\Policies;

use App\Models\Book;
use App\User;

class BookPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user, Book $book): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Book $book): bool
    {
        return true;
    }

    public function update(User $user, Book $book): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function delete(User $user, Book $book): bool
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

    public function manageTransactions(User $user, Book $book): bool
    {
        return $book->status_id == Book::STATUS_ACTIVE;
    }

    public function manageCategories(User $user, Book $book): bool
    {
        return $book->status_id == Book::STATUS_ACTIVE;
    }
}
