<?php

namespace App\Http\Livewire\Partners;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TotalSpendingToPartner extends Component
{
    public $totalSpendingToPartner;
    public $partnerTypeCode;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.total_spending_to_partner');
    }

    public function getTotalSpendingToPartner()
    {
        $this->totalSpendingToPartner = $this->calculateTotalSpendingToPartner();
        $this->isLoading = false;
    }

    private function calculateTotalSpendingToPartner()
    {
        $cacheKey = 'calculateTotalSpendingToPartner_'.$this->partnerTypeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $amount = Transaction::withoutGlobalScope('forActiveBook')
            ->whereHas('partner', function ($query) {
                $query->where('type_code', $this->partnerTypeCode);
            })
            ->where('in_out', 0)
            ->sum('amount');

        $amount = (float) $amount;

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
