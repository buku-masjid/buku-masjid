<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceInMonths extends Component
{
    public $balanceInMonthsSummary;
    public $isLoading = true;
    public $isForPrint = false;
    public $book;
    public $year;
    public $selectedMonth;
    public $startDate;
    public $endDate;
    public $startingBalance;

    public function render()
    {
        if ($this->isForPrint) {
            $this->getBalanceInMonthsSummary();
        }

        return view('livewire.dashboard.balance_in_months');
    }

    public function getBalanceInMonthsSummary()
    {
        $this->balanceInMonthsSummary = $this->calculateBalanceInMonthsSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->startDate)->subDay()->format('Y-m-d'));
        $this->isLoading = false;
    }

    private function calculateBalanceInMonthsSummary()
    {
        $cacheKey = 'calculateBalanceInMonthsSummary_'.$this->startDate->format('Y-m-d').'_'.$this->endDate->format('Y-m-d');
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $transactionSummaryInMonths = $this->getYearlyTransactionSummary($this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d'), $this->book->id);
        $months = collect(get_months());
        if ($this->selectedMonth != '00') {
            $months = $months->filter(function ($monthName, $monthNumber) {
                return $monthNumber == $this->selectedMonth;
            });
        }
        $balanceInMonthsSummary = $months->map(function ($monthName, $monthNumber) use ($transactionSummaryInMonths) {
            $transactionSummary = ['month_name' => $monthName, 'spending' => 0, 'income' => 0, 'balance' => 0];
            if (isset($transactionSummaryInMonths[$monthNumber])) {
                $transactionSummary['spending'] = $transactionSummaryInMonths[$monthNumber]->spending;
                $transactionSummary['income'] = $transactionSummaryInMonths[$monthNumber]->income;
                $transactionSummary['balance'] = $transactionSummaryInMonths[$monthNumber]->balance;
            }

            return $transactionSummary;
        });
        Cache::put($cacheKey, $balanceInMonthsSummary, $duration);

        return $balanceInMonthsSummary;
    }

    private function getYearlyTransactionSummary($startDate, $endDate, $bookId)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', YEAR(date) as year';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
            ->whereBetween('date', [$startDate, $endDate])
            ->where('book_id', $bookId);

        $reportsData = $reportQuery->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        $reports = [];
        foreach ($reportsData as $report) {
            $key = str_pad($report->month, 2, '0', STR_PAD_LEFT);
            $reports[$key] = $report;
            $reports[$key]->balance = $report->income - $report->spending;
        }

        return collect($reports);
    }
}
