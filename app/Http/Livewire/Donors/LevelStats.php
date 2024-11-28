<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class LevelStats extends Component
{
    public $partnerLevelStats;
    public $book;
    public $year;
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
        $cacheKey = 'calculatePartnerLevelStats_'.$this->partnerTypeCode.'_'.$this->year.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerLevelStats = [];
        $partnerLevels = (new Partner)->getAvailableLevels($this->partnerTypeCode);
        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        }

        $partnerTotal = Partner::where('type_code', $this->partnerTypeCode)
            ->when($this->book || $dateRange, function ($query) use ($dateRange) {
                $query->whereHas('transactions', function ($query) use ($dateRange) {
                    if ($this->book) {
                        $query->where('book_id', $this->book->id);
                    }
                    if ($dateRange) {
                        $query->whereBetween('date', $dateRange);
                    }
                });
            })->count();
        foreach ($partnerLevels as $partnerLevelCode => $partnerLevelName) {
            $partnerLevelCount = Partner::where('type_code', $this->partnerTypeCode)->where('level_code', $partnerLevelCode)
                ->when($this->book || $dateRange, function ($query) use ($dateRange) {
                    $query->whereHas('transactions', function ($query) use ($dateRange) {
                        if ($this->book) {
                            $query->where('book_id', $this->book->id);
                        }
                        if ($dateRange) {
                            $query->whereBetween('date', $dateRange);
                        }
                    });
                })->count();
            $partnerLevelPercent = get_percent($partnerLevelCount, $partnerTotal);
            $partnerLevelStats[$partnerLevelName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerLevelCount.'</strong> ('.$partnerLevelPercent.'%)'] = $partnerLevelCount;
        }

        Cache::put($cacheKey, $partnerLevelStats, $duration);

        return $partnerLevelStats;
    }
}
