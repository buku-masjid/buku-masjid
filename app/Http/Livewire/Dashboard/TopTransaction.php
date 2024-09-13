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
    public $book;
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
        $cacheKey = 'calculateTopTransactionSummary_'.$this->year.'_'.$this->typeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $inOut = $this->typeCode == 'income' ? 1 : 0;
        $topTransactionSummary = Transaction::where('in_out', $inOut)
            ->whereYear('date', $this->year)
            ->orderBy('amount', 'desc')
            ->limit(5)
            ->get();
        Cache::put($cacheKey, $topTransactionSummary, $duration);

        return $topTransactionSummary;
    }
}
