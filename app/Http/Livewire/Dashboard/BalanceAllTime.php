<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class BalanceAllTime extends Component
{
    public $balanceAllTimeSummary;
    public $isLoading = true;
    public $isForPrint = false;
    public $book;
    public $startDate;
    public $endDate;
    public $startingBalance;

    public function render()
    {
        if ($this->isForPrint) {
            $this->getBalanceAllTimeSummary();
        }

        return view('livewire.dashboard.balance_all_time');
    }

    public function getBalanceAllTimeSummary()
    {
        $this->balanceAllTimeSummary = $this->calculateBalanceAllTimeSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->startDate)->subDay()->format('Y-m-d'));
        $this->isLoading = false;
    }

    private function calculateBalanceAllTimeSummary()
    {
        $cacheKey = 'calculateBalanceAllTimeSummary_'.$this->startDate->format('Y-m-d').'_'.$this->endDate->format('Y-m-d');
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $balanceAllTimeSummary = $this->getYearlyTransactionSummary($this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d'), $this->book->id);
        Cache::put($cacheKey, $balanceAllTimeSummary, $duration);

        return $balanceAllTimeSummary;
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
            $monthNumber = str_pad($report->month, 2, '0', STR_PAD_LEFT);
            $key = $report->year.'-'.$monthNumber;
            $reports[$key] = (array) $report;
            $reports[$key]['month_number'] = $monthNumber;
            $reports[$key]['balance'] = $report->income - $report->spending;
            $reports[$key]['month_name'] = Str::limit(get_months()[$monthNumber], 3, '');
        }

        return collect($reports);
    }
}
