<?php

namespace App\Http\Resources;

use App\Transaction;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    private $transactionsOfPeriodQuery;

    private $period;

    public function __construct($resource, $transactionsOfPeriodQuery = null, $period = null)
    {
        parent::__construct($resource);

        $this->transactionsOfPeriodQuery = $transactionsOfPeriodQuery;
        $this->period = $period;
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
        $periodDateRange = $this->getPeriodDateRange();
        $incomeTotal = $this->sumAmountInPeriod($periodDateRange, Transaction::TYPE_INCOME);
        $spendingTotal = $this->sumAmountInPeriod($periodDateRange, Transaction::TYPE_SPENDING);
        $difference = $incomeTotal - $spendingTotal;
        $startBalance = $this->sumAmountBeforePeriod($periodDateRange);

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

    private function getPeriodDateRange(): array
    {
        if (is_null($this->transactionsOfPeriodQuery) || is_null($this->period)) {
            return ['first_date' => null, 'last_date' => null];
        }

        $dateRange = (clone $this->transactionsOfPeriodQuery)
            ->where('date', 'like', $this->period.'%')
            ->selectRaw('MIN(date) as first_date, MAX(date) as last_date')
            ->first();

        if (is_null($dateRange) || is_null($dateRange->first_date)) {
            $periodStartDate = $this->getPeriodStartDate();

            return ['first_date' => $periodStartDate, 'last_date' => $periodStartDate];
        }

        return ['first_date' => $dateRange->first_date, 'last_date' => $dateRange->last_date];
    }

    private function getPeriodStartDate(): string
    {
        if (strlen($this->period) === 10) {
            return $this->period;
        }

        return $this->period.'-01';
    }

    private function sumAmountBeforePeriod(array $periodDateRange): float
    {
        if (is_null($this->transactionsOfPeriodQuery) || is_null($periodDateRange['first_date'])) {
            return 0;
        }

        $incomeBefore = $this->sumAmountBeforeDate($periodDateRange['first_date'], Transaction::TYPE_INCOME);
        $spendingBefore = $this->sumAmountBeforeDate($periodDateRange['first_date'], Transaction::TYPE_SPENDING);

        return $incomeBefore - $spendingBefore;
    }

    private function sumAmountBeforeDate(string $date, int $inOut): float
    {
        return (float) (clone $this->transactionsOfPeriodQuery)
            ->where('date', '<', $date)
            ->where('in_out', $inOut)
            ->sum('amount');
    }

    private function sumAmountInPeriod(array $periodDateRange, int $inOut): float
    {
        if (is_null($this->transactionsOfPeriodQuery) || is_null($periodDateRange['first_date'])) {
            return 0;
        }

        return (float) (clone $this->transactionsOfPeriodQuery)
            ->where('in_out', $inOut)
            ->whereBetween('date', [$periodDateRange['first_date'], $periodDateRange['last_date']])
            ->sum('amount');
    }
}
