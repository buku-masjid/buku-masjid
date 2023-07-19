<?php

namespace App\Services\Transactions;

use Illuminate\Database\Eloquent\Collection;

class CsvTransformer
{
    protected $transactionCollection;

    protected $stringDelimiter = ';';

    public function __construct(Collection $transactionCollection)
    {
        $this->transactionCollection = $transactionCollection;
    }

    private function getTransactionInString()
    {
        $output = $this->getTransactionHeader();

        foreach ($this->transactionCollection as $transaction) {
            $output .= implode($this->stringDelimiter, [
                $transaction->date,
                $transaction->description,
                $transaction->in_out,
                $transaction->amount,
                optional($transaction->category)->name,
                optional($transaction->book)->name,
            ]);
            $output .= "\n";
        }

        return $output;
    }

    private function getTransactionHeader()
    {
        $headerString = implode($this->stringDelimiter, [
            __('app.date'),
            __('app.description'),
            __('transaction.in_out'),
            __('transaction.amount'),
            __('category.category'),
            __('book.book'),
        ]);

        return $headerString."\n";
    }

    public function toString()
    {
        $output = $this->getTransactionInString();

        return $output;
    }
}
