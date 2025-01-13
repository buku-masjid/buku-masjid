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
    public $month;
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
        $endDate = today()->format('Y-m-d');
        if (!in_array($this->year, [today()->format('Y')])) {
            $endDate = $this->year.'-12-31';
        }
        if (!in_array($this->month, ['00', today()->format('m')])) {
            if (in_array($this->month, array_keys(get_months()))) {
                $endDate = Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t');
            }
        }
        $endDate = Carbon::parse($endDate)->isoFormat('dddd, DD MMM YYYY');
        $this->endDate = str_replace('Minggu', 'Ahad', $endDate);
        $this->isLoading = false;
    }

    private function calculateTotalIncomeFromPartner()
    {
        $cacheKey = 'calculateTotalIncomeFromPartner_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
        }

        $transactionQuery = Transaction::withoutGlobalScope('forActiveBook')
            ->where('in_out', 1)
            ->whereBetween('date', $dateRange)
            ->whereHas('partner', function ($query) {
                $query->whereJsonContains('type_code', $this->partnerTypeCode);
            });
        if ($this->book) {
            $transactionQuery->where('book_id', $this->book->id);
        }

        $amount = (float) $transactionQuery->sum('amount');

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
