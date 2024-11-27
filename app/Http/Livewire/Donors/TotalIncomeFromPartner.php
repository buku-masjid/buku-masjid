<?php

namespace App\Http\Livewire\Donors;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TotalIncomeFromPartner extends Component
{
    public $totalIncomeFromPartner;
    public $partnerTypeCode;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.total_income_from_partner');
    }

    public function getTotalIncomeFromPartner()
    {
        $this->totalIncomeFromPartner = $this->calculateTotalIncomeFromPartner();
        $this->isLoading = false;
    }

    private function calculateTotalIncomeFromPartner()
    {
        $cacheKey = 'calculateTotalIncomeFromPartner_'.$this->partnerTypeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $amount = Transaction::withoutGlobalScope('forActiveBook')
            ->whereHas('partner', function ($query) {
                $query->where('type_code', $this->partnerTypeCode);
            })
            ->where('in_out', 1)
            ->sum('amount');

        $amount = (float) $amount;

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
