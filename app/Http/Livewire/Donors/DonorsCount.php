<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DonorsCount extends Component
{
    public $donorsCount;
    public $book;
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
        $cacheKey = 'calculateDonorsCount_'.$this->partnerTypeCode.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerQuery = Partner::where('type_code', $this->partnerTypeCode);
        if ($this->book) {
            $partnerQuery->whereHas('transactions', function ($query) {
                $query->where('book_id', $this->book->id);
            });
        }
        $amount = $partnerQuery->count();

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
