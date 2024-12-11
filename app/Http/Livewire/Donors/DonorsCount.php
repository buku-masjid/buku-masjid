<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DonorsCount extends Component
{
    public $donorsCount = [
        'total' => 0,
        'current_periode_total' => 0,
        'last_periode_total' => 0,
    ];
    public $book;
    public $year;
    public $month;
    public $partnerTypeCode = 'donatur';
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.donors_count');
    }

    public function getDonorsCount()
    {
        $this->donorsCount = $this->calculateDonorsCount();
        $this->isLoading = false;
    }

    private function calculateDonorsCount()
    {
        $cacheKey = 'calculateDonorsCount_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $donorsCount['total'] = Partner::whereJsonContains('type_code', $this->partnerTypeCode)->count();
        $donorsCount['last_periode_total'] = Partner::whereJsonContains('type_code', $this->partnerTypeCode)->count();

        $currentPeriodeDateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        $lastPeriodeDateRange = [($this->year - 1).'-01-01', ($this->year - 1).'-12-31'];
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $currentPeriodeDateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            $lastPeriodeDate = Carbon::parse($this->year.'-'.$this->month.'-01')->subDay();
            $lastPeriodeDateRange = [$lastPeriodeDate->format('Y-m').'-01', $lastPeriodeDate->format('Y-m-t')];
        }
        $partnerQuery = Partner::whereJsonContains('type_code', $this->partnerTypeCode);
        $partnerQuery->whereHas('transactions', function ($query) use ($currentPeriodeDateRange) {
            if ($this->book) {
                $query->where('book_id', $this->book->id);
            }
            $query->whereBetween('date', $currentPeriodeDateRange);
            $query->where('in_out', Transaction::TYPE_INCOME);
        });
        $donorsCount['current_periode_total'] = $partnerQuery->count();

        $partnerQuery = Partner::whereJsonContains('type_code', $this->partnerTypeCode);
        $partnerQuery->whereHas('transactions', function ($query) use ($lastPeriodeDateRange) {
            if ($this->book) {
                $query->where('book_id', $this->book->id);
            }
            $query->whereBetween('date', $lastPeriodeDateRange);
            $query->where('in_out', Transaction::TYPE_INCOME);
        });
        $donorsCount['last_periode_total'] = $partnerQuery->count();

        Cache::put($cacheKey, $donorsCount, $duration);

        return $donorsCount;
    }
}
