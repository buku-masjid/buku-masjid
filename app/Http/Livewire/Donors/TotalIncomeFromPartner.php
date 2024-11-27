<?php

namespace App\Http\Livewire\Donors;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TotalIncomeFromPartner extends Component
{
    public $totalIncomeFromPartner;
    public $book;
    public $partnerTypeCode = 'donatur';
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
        $cacheKey = 'calculateTotalIncomeFromPartner_'.$this->partnerTypeCode.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $transactionQuery = Transaction::withoutGlobalScope('forActiveBook')
            ->whereHas('partner', function ($query) {
                $query->where('type_code', $this->partnerTypeCode);
            })
            ->where('in_out', 1);

        if ($this->book) {
            $transactionQuery->where('book_id', $this->book->id);
        }

        $amount = (float) $transactionQuery->sum('amount');

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
