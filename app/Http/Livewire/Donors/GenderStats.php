<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class GenderStats extends Component
{
    public $partnerGenderStats;
    public $partnerTypeCode;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.gender_stats');
    }

    public function getGenderStats()
    {
        $this->partnerGenderStats = $this->calculateGenderStats();
        $this->isLoading = false;
    }

    private function calculateGenderStats()
    {
        $cacheKey = 'calculatePartnerGenderStats_'.$this->partnerTypeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerGenderStats = [];
        $partnerGenders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $partnerTotal = Partner::where('type_code', $this->partnerTypeCode)->count();
        foreach ($partnerGenders as $partnerGenderCode => $partnerGenderName) {
            $partnerGenderCount = Partner::where('type_code', $this->partnerTypeCode)->where('gender_code', $partnerGenderCode)->count();
            $partnerGenderPercent = get_percent($partnerGenderCount, $partnerTotal);
            $partnerGenderStats[$partnerGenderName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerGenderCount.'</strong> ('.$partnerGenderPercent.'%)'] = $partnerGenderCount;
        }

        Cache::put($cacheKey, $partnerGenderStats, $duration);

        return $partnerGenderStats;
    }
}
