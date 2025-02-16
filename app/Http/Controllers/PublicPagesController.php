<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Book;

class PublicPagesController extends Controller
{
    public function donate()
    {
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->get();
        $publicBooks = Book::where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)->get();

        return view('guest.donate', compact('bankAccounts', 'publicBooks'));
    }
}
