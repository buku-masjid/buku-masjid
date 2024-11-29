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
    public $monthText;
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
        if (!in_array($this->year, ['0000', today()->format('Y')])) {
            $endDate = $this->year.'-12-31';
        }
        if (!in_array($this->month, ['00', today()->format('m')])) {
            if (in_array($this->month, array_keys(get_months()))) {
                $endDate = Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t');
            }
        }
        $this->monthText = $this->year.'-'.$this->month == today()->format('Y-m') ? __('report.this_month') : Carbon::parse($endDate)->isoFormat('MMMM YYYY');
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

        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
            if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
                $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            }
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
