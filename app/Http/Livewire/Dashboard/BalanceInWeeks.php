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
    public $isForPrint = false;
    public $book;
    public $startDate;
    public $endDate;
    public $startingBalance;

    public function render()
    {
        if ($this->isForPrint) {
            $this->getBalanceInWeeksSummary();
        }

        return view('livewire.dashboard.balance_in_weeks');
    }

    public function getBalanceInWeeksSummary()
    {
        $this->balanceInWeekSummary = $this->calculateBalanceInWeeksSummary();
        $this->startingBalance = $this->book->getBalance(Carbon::parse($this->startDate)->subDay()->format('Y-m-d'));
        $this->isLoading = false;
    }

    private function calculateBalanceInWeeksSummary()
    {
        $cacheKey = 'calculateBalanceInWeeksSummary_'.$this->startDate->format('Y-m-d').'_'.$this->endDate->format('Y-m-d');
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $transactionSummaryInWeek = $this->getWeeklyTransactionSummary(
            $this->startDate->format('Y-m-d'),
            $this->endDate->format('Y-m-d'),
            $this->book
        );
        Cache::put($cacheKey, $transactionSummaryInWeek, $duration);

        return $transactionSummaryInWeek;
    }

    private function getWeeklyTransactionSummary($startDate, $endDate, $book)
    {
        $weekRanges = get_date_range_per_week($startDate, $endDate, $book->start_week_day_code);
        $reports = [];
        foreach ($weekRanges as $weekNumber => $weekDates) {
            $startDate = collect($weekDates)->first();
            $endDate = collect($weekDates)->last();

            $rawQuery = 'count(`id`) as count';
            $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
            $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

            $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
                ->whereBetween('date', [$startDate, $endDate])
                ->where('book_id', $book->id);

            $reportData = $reportQuery->get()->first();
            $reportData->start_date = $startDate;
            $reportData->end_date = $endDate;
            $reportData->date_range_text = get_date_range_text($startDate, $endDate);
            $reportData->income = $reportData->income ?: 0;
            $reportData->spending = $reportData->spending ?: 0;
            $reportData->balance = $reportData->income - $reportData->spending;

            $reports[$weekNumber] = $reportData;
        }

        return collect($reports);
    }
}
