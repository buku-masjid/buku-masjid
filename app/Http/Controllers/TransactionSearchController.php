<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionSearchController extends Controller
{
    public function index(Request $request)
    {
        $defaultStartDate = date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-t'));

        $transactions = collect([]);
        $searchQuery = $request->get('search_query');
        $categoryId = $request->get('category_id');
        $bankAccountId = $request->get('bank_account_id');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->whereBetween('date', [$startDate, $endDate]);
            if ($searchQuery != '---') {
                $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            }
            if ($categoryId) {
                $transactionQuery->where('category_id', $categoryId);
            }
            if ($bankAccountId) {
                if ($bankAccountId == 'null') {
                    $transactionQuery->whereNull('bank_account_id');
                } else {
                    $transactionQuery->where('bank_account_id', $bankAccountId);
                }
            }
            $transactions = $transactionQuery->with('category', 'bankAccount', 'book')->limit(100)->get();
        }
        $categories = $this->getCategoryList();
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id')
            ->prepend(__('transaction.cash'), 'null');

        return view('transaction_search.index', compact(
            'searchQuery', 'transactions', 'startDate', 'endDate', 'categories', 'bankAccounts'
        ));
    }
}
