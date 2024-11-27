<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class LevelStats extends Component
{
    public $partnerLevelStats;
    public $book;
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
        $cacheKey = 'calculatePartnerLevelStats_'.$this->partnerTypeCode.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerLevelStats = [];
        $partnerLevels = (new Partner)->getAvailableLevels($this->partnerTypeCode);
        $partnerTotal = Partner::where('type_code', $this->partnerTypeCode)->count();
        foreach ($partnerLevels as $partnerLevelCode => $partnerLevelName) {
            $partnerLevelCount = Partner::where('type_code', $this->partnerTypeCode)->where('level_code', $partnerLevelCode)->count();
            $partnerLevelPercent = get_percent($partnerLevelCount, $partnerTotal);
            $partnerLevelStats[$partnerLevelName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerLevelCount.'</strong> ('.$partnerLevelPercent.'%)'] = $partnerLevelCount;
        }

        Cache::put($cacheKey, $partnerLevelStats, $duration);

        return $partnerLevelStats;
    }
}
