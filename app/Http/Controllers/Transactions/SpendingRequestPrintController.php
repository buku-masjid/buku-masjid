<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class SpendingRequestPrintController extends Controller
{
    public function show(Request $request, Transaction $transaction)
    {
        if ($request->get('in_out') == 1) {
            return redirect()->route('transactions.show', $transaction);
        }

        // return view('transactions.spending_request_pdf', compact('transaction'));

        $pdf = \PDF::loadView('transactions.spending_request_pdf', compact('transaction'));

        return $pdf->stream(__('transaction.print_spending_request').' '.$transaction->id.'.pdf');
    }
}
