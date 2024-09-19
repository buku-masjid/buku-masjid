<?php

namespace App\Http\Livewire\Dashboard;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TopTransaction extends Component
{
    public $topTransactionSummary;
    public $isLoading = true;
    public $year;
    public $startDate;
    public $endDate;
    public $typeCode;

    public function render()
    {
        return view('livewire.dashboard.top_transaction');
    }

    public function getTopTransactionSummary()
    {
        $this->topTransactionSummary = $this->calculateTopTransactionSummary();
        $this->isLoading = false;
    }

    private function calculateTopTransactionSummary()
    {
        $cacheKey = 'calculateTopTransactionSummary_'.$this->startDate.'_'.$this->endDate.'_'.$this->typeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $inOut = $this->typeCode == 'income' ? 1 : 0;
        $topTransactionSummary = Transaction::where('in_out', $inOut)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('amount', 'desc')
            ->limit(5)
            ->get();
        Cache::put($cacheKey, $topTransactionSummary, $duration);

        return $topTransactionSummary;
    }
}
