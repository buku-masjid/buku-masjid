<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show(Book $book)
    {
        return view('books.landing_page.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.landing_page.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $bookData = $request->validate([
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'landing_page_content' => ['nullable', 'max:3000'],
        ]);
        $this->updateBookSettings($book, $bookData);
        flash(__('book.updated'), 'success');

        return redirect()->route('books.landing_page.show', $book);
    }

    private function updateBookSettings(Book $book, array $bookData): void
    {
        array_key_exists('due_date', $bookData) ? Setting::for($book)->set('due_date', $bookData['due_date']) : null;
        array_key_exists('landing_page_content', $bookData) ? Setting::for($book)->set('landing_page_content', $bookData['landing_page_content']) : null;
    }

}
