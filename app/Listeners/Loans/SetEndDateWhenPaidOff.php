<?php

namespace App\Listeners\Loans;

use App\Events\Loans\PaymentCreated as LoanPaymentCreated;

class SetEndDateWhenPaidOff
{
    public function handle(LoanPaymentCreated $event)
    {
        if ($event->loan->amount > $event->loan->payment_total) {
            return;
        }

        $event->loan->end_date = now()->format('Y-m-d');
        $event->loan->save();
    }
}
