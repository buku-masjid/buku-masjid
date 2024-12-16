<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class LevelStats extends Component
{
    public $partnerLevelStats;
    public $book;
    public $year;
    public $month;
    public $partnerTypeCode = 'donatur';
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.level_stats');
    }

    public function getLevelStats()
    {
        $this->partnerLevelStats = $this->calculateLevelStats();
        $this->isLoading = false;
    }

    private function calculateLevelStats()
    {
        $cacheKey = 'calculatePartnerLevelStats_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerLevelStats = [];
        $partnerLevels = (new Partner)->getAvailableLevels([$this->partnerTypeCode]);
        $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
        }

        $partnerTotal = Partner::whereJsonContains('type_code', $this->partnerTypeCode)
            ->whereHas('transactions', function ($query) use ($dateRange) {
                if ($this->book) {
                    $query->where('book_id', $this->book->id);
                }
                $query->whereBetween('date', $dateRange);
                $query->where('in_out', Transaction::TYPE_INCOME);
            })->count();

        foreach ($partnerLevels['Donatur'] as $partnerLevelCode => $partnerLevelName) {
            $partnerLevelCount = Partner::whereJsonContains('type_code', $this->partnerTypeCode)
                ->whereJsonContains('level_code', ['donatur' => $partnerLevelCode])
                ->whereHas('transactions', function ($query) use ($dateRange) {
                    if ($this->book) {
                        $query->where('book_id', $this->book->id);
                    }
                    $query->whereBetween('date', $dateRange);
                    $query->where('in_out', Transaction::TYPE_INCOME);
                })->count();
            $partnerLevelPercent = get_percent($partnerLevelCount, $partnerTotal);
            $partnerLevelStats[$partnerLevelName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerLevelCount.'</strong> ('.$partnerLevelPercent.'%)'] = $partnerLevelCount;
        }

        Cache::put($cacheKey, $partnerLevelStats, $duration);

        return $partnerLevelStats;
    }
}
