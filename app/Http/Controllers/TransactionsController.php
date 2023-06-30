<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\CreateRequest;
use App\Http\Requests\Transactions\UpdateRequest;
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
        $defaultStartDate = auth()->user()->account_start_date;
        $startDate = $defaultStartDate ?: $year.'-'.$month.'-01';

        $transactions = $this->getTansactionsPerWeek($yearMonth);
        // dd($transactions->toArray());

        $categories = $this->getCategoryList()->prepend('-- '.__('transaction.no_category').' --', 'null');
        $partners = $this->getPartnerList()->prepend('-- '.__('transaction.no_partner').' --', 'null');

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        return view('transactions.index', compact(
            'transactions', 'editableTransaction',
            'yearMonth', 'month', 'year', 'categories',
            'incomeTotal', 'spendingTotal', 'partners',
            'startDate', 'date'
        ));
    }

    private function getTansactionsPerWeek(string $yearMonth)
    {
        $transactionsPerWeek = [];
        $transactions = $this->getTansactions($yearMonth);
        foreach (get_date_range_per_week($yearMonth) as $weekKey => $dateNumbers) {
            $transactionsPerWeek[$weekKey] = $transactions->filter(function ($transaction) use ($dateNumbers) {
                return in_array($transaction->date, $dateNumbers);
            });
        }

        return collect($transactionsPerWeek);
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
            if ($referencePage == 'partner') {
                if ($transaction->partner) {
                    return redirect()->route('partners.show', [
                        $transaction->partner_id,
                        'start_date' => $transactionUpateForm->get('start_date'),
                        'end_date' => $transactionUpateForm->get('end_date'),
                        'category_id' => $transactionUpateForm->get('category_id'),
                        'query' => $transactionUpateForm->get('query'),
                    ]);
                }
            }
            if ($referencePage == 'category') {
                if ($transaction->category) {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => $transactionUpateForm->get('start_date'),
                        'end_date' => $transactionUpateForm->get('end_date'),
                        'partner_id' => $transactionUpateForm->get('partner_id'),
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
                if ($referencePage == 'partner') {
                    return redirect()->route('partners.show', [
                        $transaction->partner_id,
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'category_id' => request('queried_category_id'),
                        'query' => request('query'),
                    ]);
                }
                if ($referencePage == 'category') {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'partner_id' => request('queried_partner_id'),
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
