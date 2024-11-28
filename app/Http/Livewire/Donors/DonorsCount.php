<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DonorsCount extends Component
{
    public $donorsCount;
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

        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
            if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
                $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            }
        }
        $partnerQuery = Partner::where('type_code', $this->partnerTypeCode);
        if ($this->book || $dateRange) {
            $partnerQuery->whereHas('transactions', function ($query) use ($dateRange) {
                if ($this->book) {
                    $query->where('book_id', $this->book->id);
                }
                if ($dateRange) {
                    $query->whereBetween('date', $dateRange);
                }
                $query->where('in_out', Transaction::TYPE_INCOME);
            });
        }

        $amount = $partnerQuery->count();

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
