<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Transaction;

class ReceiptPrintController extends Controller
{
    public function show(Transaction $transaction)
    {
        // return view('transactions.receipt_pdf', compact('transaction'));

        $pdf = \PDF::loadView('transactions.receipt_pdf', compact('transaction'));

        return $pdf->stream(__('transaction.print_receipt').' '.$transaction->id.'.pdf');
    }
}
