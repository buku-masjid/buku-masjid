<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookSwitcherController extends Controller
{

    public function store(Request $request)
    {
        $validatedPayload = $request->validate([
            'switch_book' => ['required', 'exists:books,id'],
        ]);

        auth()->setActiveBook($validatedPayload['switch_book']);
        flash(__('book.switched', ['book_name' => auth()->activeBook()->name]));

        return back();
    }
}
