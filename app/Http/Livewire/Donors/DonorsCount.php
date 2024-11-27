<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DonorsCount extends Component
{
    public $donorsCount;
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
        $cacheKey = 'calculateDonorsCount';
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $amount = Partner::count();

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
