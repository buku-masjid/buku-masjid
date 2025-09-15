<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Book;

class PublicBookController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->get();
        $publicBooks = Book::where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();

        return view('guest.books.index', compact('bankAccounts', 'publicBooks'));
    }

    public function show(Book $book)
    {
        return view('guest.books.show', compact('book'));
    }
}
