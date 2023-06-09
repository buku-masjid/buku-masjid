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
        $partnerId = $request->get('partner_id');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->whereBetween('date', [$startDate, $endDate]);
            if ($searchQuery != '---') {
                $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            }
            if ($categoryId) {
                $transactionQuery->where('category_id', $categoryId);
            }
            if ($partnerId) {
                $transactionQuery->where('partner_id', $partnerId);
            }
            $transactions = $transactionQuery->with('category', 'partner', 'loan')->limit(100)->get();
        }
        $partners = $this->getPartnerList();
        $categories = $this->getCategoryList();

        return view('transaction_search.index', compact(
            'searchQuery', 'transactions', 'startDate', 'endDate', 'partners', 'categories'
        ));
    }
}
