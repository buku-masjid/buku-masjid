<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceInWeeks extends Component
{
    public $balanceInWeekSummary;
    public $isLoading = true;
    public $book;
    public $year;
    public $selectedMonth;
    public $startDate;
    public $endDate;
    public $startingBalance;

    public function render()
    {
        return view('livewire.dashboard.balance_by_weeks');
    }

    public function getBalanceInWeeksSummary()
    {
        $this->balanceInWeekSummary = $this->calculateBalanceInWeeksSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->startDate)->subDay()->format('Y-m-d'));
        $this->isLoading = false;
    }

    private function calculateBalanceInWeeksSummary()
    {
        $cacheKey = 'calculateBalanceInWeeksSummary_'.$this->startDate.'_'.$this->endDate;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $transactionSummaryInWeek = $this->getYearlyTransactionSummary($this->startDate, $this->endDate, $this->book->id);
        $months = collect(get_months());
        if ($this->selectedMonth != '00') {
            $months = $months->filter(function ($monthName, $monthNumber) {
                return $monthNumber == $this->selectedMonth;
            });
        }
        $balanceInWeekSummary = $months->map(function ($monthName, $monthNumber) use ($transactionSummaryInWeek) {
            $transactionSummary = ['month_name' => $monthName, 'spending' => 0, 'income' => 0, 'balance' => 0];
            if (isset($transactionSummaryInWeek[$monthNumber])) {
                $transactionSummary['spending'] = $transactionSummaryInWeek[$monthNumber]->spending;
                $transactionSummary['income'] = $transactionSummaryInWeek[$monthNumber]->income;
                $transactionSummary['balance'] = $transactionSummaryInWeek[$monthNumber]->balance;
            }

            return $transactionSummary;
        });
        Cache::put($cacheKey, $balanceInWeekSummary, $duration);

        return $balanceInWeekSummary;
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
