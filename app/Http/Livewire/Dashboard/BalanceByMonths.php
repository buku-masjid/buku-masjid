<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceByMonths extends Component
{
    public $balanceByMonthSummary;
    public $isLoading = true;
    public $book;
    public $year;
    public $startDate;
    public $endDate;
    public $startingBalance;

    public function render()
    {
        return view('livewire.dashboard.balance_by_months');
    }

    public function getBalanceByMonthsSummary()
    {
        $this->balanceByMonthSummary = $this->calculateBalanceByMonthsSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->startDate)->subDay()->format('Y-m-d'));
        $this->year = substr($this->startDate, 0, 4);
        $this->isLoading = false;
    }

    private function calculateBalanceByMonthsSummary()
    {
        $cacheKey = 'calculateBalanceByMonthsSummary_'.$this->startDate.'_'.$this->endDate;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $transactionSummaryByMonth = $this->getYearlyTransactionSummary($this->startDate, $this->endDate, $this->book->id);
        $balanceByMonthSummary = collect(get_months())->map(function ($monthName, $monthNumber) use ($transactionSummaryByMonth) {
            $transactionSummary = ['month_name' => $monthName, 'spending' => 0, 'income' => 0, 'balance' => 0];
            if (isset($transactionSummaryByMonth[$monthNumber])) {
                $transactionSummary['spending'] = $transactionSummaryByMonth[$monthNumber]->spending;
                $transactionSummary['income'] = $transactionSummaryByMonth[$monthNumber]->income;
                $transactionSummary['balance'] = $transactionSummaryByMonth[$monthNumber]->balance;
            }

            return $transactionSummary;
        });
        Cache::put($cacheKey, $balanceByMonthSummary, $duration);

        return $balanceByMonthSummary;
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
