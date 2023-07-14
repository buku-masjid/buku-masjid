<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionSearchController extends Controller
{
    public function index(Request $request)
    {
        $defaultStartDate = auth()->user()->account_start_date ?: date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-t'));

        $transactions = collect([]);
        $searchQuery = $request->get('search_query');
        $categoryId = $request->get('category_id');
        $bookId = $request->get('book_id');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->whereBetween('date', [$startDate, $endDate]);
            if ($searchQuery != '---') {
                $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            }
            if ($categoryId) {
                $transactionQuery->where('category_id', $categoryId);
            }
            if ($bookId) {
                $transactionQuery->where('book_id', $bookId);
            }
            $transactions = $transactionQuery->with('category', 'book')->limit(100)->get();
        }
        $books = $this->getBookList();
        $categories = $this->getCategoryList();

        return view('transaction_search.index', compact(
            'searchQuery', 'transactions', 'startDate', 'endDate', 'books', 'categories'
        ));
    }
}
