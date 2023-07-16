<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Book);

        $newBook = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newBook['creator_id'] = auth()->id();

        $book = Book::create($newBook);

        return response()->json([
            'message' => __('book.created'),
            'data' => $book,
        ], 201);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $bookData = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $book->update($bookData);

        return response()->json([
            'message' => __('book.updated'),
            'data' => $book,
        ]);
    }

    public function destroy(Request $request, Book $book)
    {
        $this->authorize('delete', $book);

        $bookData = $request->validate(['book_id' => 'required']);

        if ($bookData['book_id'] == $book->id && $book->delete()) {
            return response()->json(['message' => __('book.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }
}
