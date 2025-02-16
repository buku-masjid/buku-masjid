<?php

namespace App\Http\Controllers;

use App\Models\Book;

class PublicBookController extends Controller
{
    public function show(Book $book)
    {
        return view('guest.books.show', compact('book'));
    }
}
