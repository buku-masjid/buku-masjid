<?php

namespace App\Http\Resources;

use App\Transaction;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Unfetched query of every transaction of the active book, with the request filters applied.
     *
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    private $periodQuery;

    /**
     * Period in `Y-m` or `Y-m-d` format, as resolved from the request.
     *
     * @var string|null
     */
    private $yearMonth;

    /**
     * @param  mixed  $resource
     * @param  \Illuminate\Database\Eloquent\Builder|null  $periodQuery
     * @param  string|null  $yearMonth
     */
    public function __construct($resource, $periodQuery = null, $yearMonth = null)
    {
        parent::__construct($resource);

        $this->periodQuery = $periodQuery;
        $this->yearMonth = $yearMonth;
    }

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
        $period = $this->getPeriod();
        $incomeTotal = $this->getTransactionTotalOfThePeriod($period, Transaction::TYPE_INCOME);
        $spendingTotal = $this->getTransactionTotalOfThePeriod($period, Transaction::TYPE_SPENDING);
        $difference = $incomeTotal - $spendingTotal;
        $startBalance = $this->getStartBalance($period);

        return [
            'stats' => [
                'start_balance' => format_number($startBalance),
                'income_total' => format_number($incomeTotal),
                'spending_total' => format_number($spendingTotal),
                'difference' => format_number($difference),
                'end_balance' => format_number($startBalance + $difference),
            ],
        ];
    }

    /**
     * The earliest and the latest transaction date of the requested period.
     *
     * The boundaries come from the dates of the whole period, never from the
     * position of a transaction inside the returned collection.
     *
     * @return array{start_date: ?string, end_date: ?string}
     */
    private function getPeriod(): array
    {
        if (is_null($this->periodQuery) || is_null($this->yearMonth)) {
            return ['start_date' => null, 'end_date' => null];
        }

        $aggregate = (clone $this->periodQuery)
            ->where('date', 'like', $this->yearMonth.'%')
            ->selectRaw('MIN(date) as start_date, MAX(date) as end_date')
            ->first();

        return [
            'start_date' => $aggregate ? $aggregate->start_date : null,
            'end_date' => $aggregate ? $aggregate->end_date : null,
        ];
    }

    /**
     * The balance right before the earliest transaction of the period.
     *
     * It is computed from the same filtered query as the totals, so that
     * `start_balance + difference = end_balance` always holds — also when a
     * category, book, bank account, or description filter is applied.
     */
    private function getStartBalance(array $period): float
    {
        if (is_null($this->periodQuery) || is_null($period['start_date'])) {
            return 0;
        }

        $incomeBefore = (float) (clone $this->periodQuery)
            ->where('date', '<', $period['start_date'])
            ->where('in_out', Transaction::TYPE_INCOME)
            ->sum('amount');

        $spendingBefore = (float) (clone $this->periodQuery)
            ->where('date', '<', $period['start_date'])
            ->where('in_out', Transaction::TYPE_SPENDING)
            ->sum('amount');

        return $incomeBefore - $spendingBefore;
    }

    /**
     * Total amount of one transaction type across the whole period.
     */
    private function getTransactionTotalOfThePeriod(array $period, int $inOut): float
    {
        if (is_null($this->periodQuery) || is_null($period['start_date'])) {
            return 0;
        }

        return (float) (clone $this->periodQuery)
            ->where('in_out', $inOut)
            ->whereBetween('date', [$period['start_date'], $period['end_date']])
            ->sum('amount');
    }
}
