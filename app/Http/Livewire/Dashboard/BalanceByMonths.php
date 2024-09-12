<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class BalanceByMonths extends Component
{
    public $balanceByMonthSummary;
    public $isLoading = true;
    public $year;

    public function render()
    {
        return view('livewire.dashboard.balance_by_months');
    }

    public function getBalanceByMonthsSummary()
    {
        $this->balanceByMonthSummary = $this->calculateBalanceByMonthsSummary();
        sleep(3);
        $this->isLoading = false;
    }

    private function calculateBalanceByMonthsSummary()
    {
        $cacheKey = 'calculateBalanceByMonthsSummary_'.$this->year;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $balanceByMonthSummary = [];
        Cache::put($cacheKey, $balanceByMonthSummary, $duration);

        return $balanceByMonthSummary;
    }
}
