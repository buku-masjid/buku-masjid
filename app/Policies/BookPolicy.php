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
        return true;
    }

    public function view(User $user, Book $book)
    {
        return $user->id == $book->creator_id;
    }

    public function update(User $user, Book $book)
    {
        return $user->id == $book->creator_id;
    }

    public function delete(User $user, Book $book)
    {
        return $user->id == $book->creator_id;
    }
}
