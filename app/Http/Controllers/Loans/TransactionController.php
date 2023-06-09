<?php

namespace App\Http\Controllers\Loans;

use App\Events\Loans\PaymentCreated as LoanPaymentCreated;
use App\Http\Controllers\Controller;
use App\Loan;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request, Loan $loan)
    {
        $newTransaction = $request->validate([
            'amount'      => 'required|numeric',
            'date'        => 'required|date_format:Y-m-d',
            'in_out'      => 'required|boolean',
            'description' => 'required|max:255',
        ]);
        $newTransaction['loan_id'] = $loan->id;
        $newTransaction['partner_id'] = $loan->partner_id;
        $newTransaction['creator_id'] = auth()->id();
        $transaction = Transaction::create($newTransaction);

        event(new LoanPaymentCreated($loan, $transaction));

        return redirect()->route('loans.show', $loan);
    }
}
