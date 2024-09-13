<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DailyAverages extends Component
{
    public $dailyAveragesSummary;
    public $isLoading = true;
    public $year;
    public $book;
    public $typeCode;

    public function render()
    {
        return view('livewire.dashboard.daily_averages');
    }

    public function getDailyAveragesSummary()
    {
        $this->dailyAveragesSummary = $this->calculateDailyAveragesSummary();
        $this->isLoading = false;
    }

    private function calculateDailyAveragesSummary()
    {
        $cacheKey = 'calculateDailyAveragesSummary_'.$this->year;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $rawSelect = "";
        $rawSelect .= "sum(amount) as total";
        $rawSelect .= " , in_out";

        $dailyAveragesSummary = DB::table('transactions')
            ->selectRaw($rawSelect)
            ->where('book_id', $this->book->id)
            ->whereYear('date', $this->year)
            ->groupBy('in_out')
            ->orderBy('in_out', 'desc')
            ->get();
        $endDate = $this->year == now()->format('Y') ? now()->format('Y-m-d') : $this->year.'-12-31';
        $dayCount = Carbon::parse($this->year.'-01-01')->diffInDays($endDate);
        $dailyAveragesSummary->each(function ($totalTransaction) use ($dayCount) {
            $description = __('transaction.spending');
            if ($totalTransaction->in_out == 1) {
                $description = __('transaction.income');
            }
            $totalTransaction->description = $description.' / '.__('time.day_name');
            $totalTransaction->day_count = $dayCount;
            $totalTransaction->average = $totalTransaction->total / $dayCount;
        });
        Cache::put($cacheKey, $dailyAveragesSummary, $duration);

        return $dailyAveragesSummary;
    }
}
