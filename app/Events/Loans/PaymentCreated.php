<?php

namespace App\Events\Loans;

use App\Loan;
use App\Transaction;

class PaymentCreated
{
    public $loan;

    public $transaction;

    public function __construct(Loan $loan, Transaction $transaction)
    {
        $this->loan = $loan;
        $this->transaction = $transaction;
    }
}
