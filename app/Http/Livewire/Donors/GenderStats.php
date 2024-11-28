<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class GenderStats extends Component
{
    public $partnerGenderStats;
    public $book;
    public $year;
    public $month;
    public $partnerTypeCode = 'donatur';
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
        $cacheKey = 'calculatePartnerGenderStats_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerGenderStats = [];
        $partnerGenders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
            if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
                $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            }
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
                    $query->where('in_out', Transaction::TYPE_INCOME);
                });
            })->count();
        foreach ($partnerGenders as $partnerGenderCode => $partnerGenderName) {
            $partnerGenderCount = Partner::where('type_code', $this->partnerTypeCode)
                ->where('gender_code', $partnerGenderCode)
                ->whereHas('transactions', function ($query) use ($dateRange) {
                    if ($this->book) {
                        $query->where('book_id', $this->book->id);
                    }
                    if ($dateRange) {
                        $query->whereBetween('date', $dateRange);
                    }
                    $query->where('in_out', Transaction::TYPE_INCOME);
                })->count();
            $partnerGenderPercent = get_percent($partnerGenderCount, $partnerTotal);
            $partnerGenderStats[$partnerGenderName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerGenderCount.'</strong> ('.$partnerGenderPercent.'%)'] = $partnerGenderCount;
        }

        Cache::put($cacheKey, $partnerGenderStats, $duration);

        return $partnerGenderStats;
    }
}
