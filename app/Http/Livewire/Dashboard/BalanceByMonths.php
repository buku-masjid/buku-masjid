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
    public $startingBalance;

    public function render()
    {
        return view('livewire.dashboard.balance_by_months');
    }

    public function getBalanceByMonthsSummary()
    {
        $this->balanceByMonthSummary = $this->calculateBalanceByMonthsSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->year.'-01-01')->subDay()->format('Y-m-d'));
        $this->isLoading = false;
    }

    private function calculateBalanceByMonthsSummary()
    {
        $cacheKey = 'calculateBalanceByMonthsSummary_'.$this->year;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $transactionSummaryByMonth = $this->getYearlyTransactionSummary($this->year, $this->book->id);
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

    private function getYearlyTransactionSummary($year, $bookId)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', YEAR(date) as year';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
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
