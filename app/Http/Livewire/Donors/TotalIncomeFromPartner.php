<?php

namespace App\Http\Livewire\Donors;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TotalIncomeFromPartner extends Component
{
    public $totalIncomeFromPartner;
    public $book;
    public $year;
    public $endDate;
    public $partnerTypeCode = 'donatur';
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.total_income_from_partner');
    }

    public function getTotalIncomeFromPartner()
    {
        $this->totalIncomeFromPartner = $this->calculateTotalIncomeFromPartner();
        $this->endDate = today()->isoFormat('dddd, DD MMM YYYY');
        if (!in_array($this->year, ['0000', today()->format('Y')])) {
            $endDate = Carbon::parse($this->year.'-12-31')->isoFormat('dddd, DD MMM YYYY');
            $this->endDate = str_replace('Minggu', 'Ahad', $endDate);
        }
        $this->isLoading = false;
    }

    private function calculateTotalIncomeFromPartner()
    {
        $cacheKey = 'calculateTotalIncomeFromPartner_'.$this->partnerTypeCode.'_'.$this->year.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        }

        $transactionQuery = Transaction::withoutGlobalScope('forActiveBook')
            ->whereHas('partner', function ($query) {
                $query->where('type_code', $this->partnerTypeCode);
            })
            ->where('in_out', 1);

        if ($this->book) {
            $transactionQuery->where('book_id', $this->book->id);
        }
        if ($dateRange) {
            $transactionQuery->whereBetween('date', $dateRange);
        }

        $amount = (float) $transactionQuery->sum('amount');

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
