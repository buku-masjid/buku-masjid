<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        $transactions = $this->resource;
        $incomeTotal = $this->getIncomeTransactionTotal();
        $spendingTotal = $this->getSpendingTransactionTotal();
        $startBalance = 0;
        $endBalance = 0;
        if ($transactions->first()) {
            $startBalance = auth()->activeBook()->getBalance(
                Carbon::parse($transactions->first()->date)
                    ->subDay()->format('Y-m-d')
            );
        }
        if ($transactions->last()) {
            $endBalance = auth()->activeBook()->getBalance($transactions->last()->date);
        }

        return [
            'stats' => [
                'start_balance' => format_number($startBalance),
                'income_total' => format_number($incomeTotal),
                'spending_total' => format_number($spendingTotal),
                'difference' => format_number($incomeTotal - $spendingTotal),
                'end_balance' => format_number($endBalance),
            ],
        ];
    }

    private function getIncomeTransactionTotal()
    {
        return $this->resource->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : 0;
        });
    }

    private function getSpendingTransactionTotal()
    {
        return $this->resource->sum(function ($transaction) {
            return $transaction->in_out ? 0 : $transaction->amount;
        });
    }
}
