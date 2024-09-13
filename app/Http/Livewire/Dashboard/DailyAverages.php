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
    public $startDate;
    public $endDate;
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
        $cacheKey = 'calculateDailyAveragesSummary_'.$this->startDate.'_'.$this->endDate;
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
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('in_out')
            ->orderBy('in_out', 'desc')
            ->get();
        $dayCount = Carbon::parse($this->startDate)->diffInDays($this->endDate);
        $dailyAveragesSummary->each(function ($totalTransaction) use ($dayCount) {
            $typeCode = $totalTransaction->in_out == 1 ? 'income' : 'spending';
            $totalTransaction->type_code = $typeCode;
            $totalTransaction->description = __('transaction.'.$typeCode).' / '.__('time.day_name');
            $totalTransaction->day_count = $dayCount;
            $totalTransaction->average = $totalTransaction->total / $dayCount;
        });
        Cache::put($cacheKey, $dailyAveragesSummary, $duration);

        return $dailyAveragesSummary;
    }
}
