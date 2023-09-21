<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\CreateRequest;
use App\Http\Requests\Transactions\UpdateRequest;
use App\Models\Category;
use App\Transaction;

class TransactionsController extends Controller
{
    public function index()
    {
        $editableTransaction = null;
        $yearMonth = $this->getYearMonth();
        $date = request('date');
        $year = request('year', date('Y'));
        $month = request('month', date('m'));
        $startDate = $year.'-'.$month.'-01';

        $transactions = $this->getTansactions($yearMonth);

        $categories = $this->getCategoryList()->prepend('-- '.__('transaction.no_category').' --', 'null');

        if (in_array(request('action'), ['add-income'])) {
            $categories = Category::orderBy('name')
                ->where('color', config('masjid.income_color'))
                ->where('status_id', Category::STATUS_ACTIVE)
                ->pluck('name', 'id');
        }

        if (in_array(request('action'), ['add-spending'])) {
            $categories = Category::orderBy('name')
                ->where('color', config('masjid.spending_color'))
                ->where('status_id', Category::STATUS_ACTIVE)
                ->pluck('name', 'id');
        }

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        return view('transactions.index', compact(
            'transactions', 'editableTransaction',
            'yearMonth', 'month', 'year', 'categories',
            'incomeTotal', 'spendingTotal',
            'startDate', 'date'
        ));
    }

    public function store(CreateRequest $transactionCreateForm)
    {
        $transaction = $transactionCreateForm->save();

        if ($transaction['in_out']) {
            flash(__('transaction.income_added'), 'success');
        } else {
            flash(__('transaction.spending_added'), 'success');
        }

        return redirect()->route('transactions.index', [
            'month' => $transaction->month, 'year' => $transaction->year,
        ]);
    }

    public function update(UpdateRequest $transactionUpateForm, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction = $transactionUpateForm->save();

        flash(__('transaction.updated'), 'success');

        if ($referencePage = $transactionUpateForm->get('reference_page')) {
            if ($referencePage == 'category') {
                if ($transaction->category) {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => $transactionUpateForm->get('start_date'),
                        'end_date' => $transactionUpateForm->get('end_date'),
                        'book_id' => $transactionUpateForm->get('book_id'),
                        'query' => $transactionUpateForm->get('query'),
                    ]);
                }
            }
        }

        return redirect()->route('transactions.index', [
            'month' => $transaction->month,
            'year' => $transaction->year,
            'category_id' => $transactionUpateForm->get('queried_category_id'),
            'query' => $transactionUpateForm->get('query'),
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        request()->validate(['transaction_id' => 'required']);
        if (request('transaction_id') == $transaction->id && $transaction->delete()) {
            flash(__('transaction.deleted'), 'warning');

            if ($referencePage = request('reference_page')) {
                if ($referencePage == 'category') {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'book_id' => request('queried_book_id'),
                        'query' => request('query'),
                    ]);
                }
            }

            return redirect()->route('transactions.index', [
                'month' => $transaction->month, 'year' => $transaction->year,
            ]);
        }

        flash(__('transaction.undeleted'), 'error');

        return back();
    }
}
