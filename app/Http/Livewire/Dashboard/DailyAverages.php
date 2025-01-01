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
    public $isForPrint = false;
    public $startDate;
    public $endDate;
    public $book;
    public $typeCode;

    public function render()
    {
        if ($this->isForPrint) {
            $this->getDailyAveragesSummary();
        }

        return view('livewire.dashboard.daily_averages');
    }

    public function getDailyAveragesSummary()
    {
        $this->dailyAveragesSummary = $this->calculateDailyAveragesSummary();
        $this->isLoading = false;
    }

    private function calculateDailyAveragesSummary()
    {
        $cacheKey = 'calculateDailyAveragesSummary_'.$this->startDate->format('Y-m-d').'_'.$this->endDate->format('Y-m-d');
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $dailyAveragesSummary = DB::table('transactions')
            ->selectRaw('sum(amount) as total, in_out')
            ->where('book_id', $this->book->id)
            ->whereBetween('date', [$this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d')])
            ->groupBy('in_out')
            ->orderBy('in_out', 'desc')
            ->get();
        $dayCount = Carbon::parse($this->startDate)->diffInDays($this->endDate);
        $dailyAveragesSummary->each(function ($totalTransaction) use ($dayCount) {
            $typeCode = $totalTransaction->in_out == 1 ? 'income' : 'spending';
            $totalTransaction->type_code = $typeCode;
            $totalTransaction->description = __('transaction.'.$typeCode).' / '.__('time.day_name');
            $totalTransaction->day_count = $dayCount;
            $totalTransaction->average = $dayCount ? ($totalTransaction->total / $dayCount) : 0;
        });
        Cache::put($cacheKey, $dailyAveragesSummary, $duration);

        return $dailyAveragesSummary;
    }
}
