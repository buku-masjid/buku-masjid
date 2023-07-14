<?php

namespace App\Policies;

use App\Book;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create book.
     *
     * @param  \App\User  $user
     * @param  \App\Book  $book
     * @return mixed
     */
    public function create(User $user, Book $book)
    {
        // Update $user authorization to create $book here.
        return true;
    }

    /**
     * Determine whether the user can view the book.
     *
     * @param  \App\User  $user
     * @param  \App\Book  $book
     * @return mixed
     */
    public function view(User $user, Book $book)
    {
        // Update $user authorization to view $book here.
        return $user->id == $book->creator_id;
    }

    /**
     * Determine whether the user can update the book.
     *
     * @param  \App\User  $user
     * @param  \App\Book  $book
     * @return mixed
     */
    public function update(User $user, Book $book)
    {
        // Update $user authorization to update $book here.
        return $user->id == $book->creator_id;
    }

    /**
     * Determine whether the user can delete the book.
     *
     * @param  \App\User  $user
     * @param  \App\Book  $book
     * @return mixed
     */
    public function delete(User $user, Book $book)
    {
        // Update $user authorization to delete $book here.
        return $user->id == $book->creator_id;
    }
}
