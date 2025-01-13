<?php

namespace App\Http\Livewire\Donors;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class IncomeStats extends Component
{
    public $partnerIncomeStats = [
        'last_month_total' => 0,
        'last_month_name' => '',
        'current_year_total' => 0,
        'current_year_name' => '',
    ];
    public $book;
    public $year;
    public $month;
    public $partnerTypeCode = 'donatur';
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.income_stats');
    }

    public function getIncomeStats()
    {
        $this->partnerIncomeStats = $this->calculateIncomeStats();
        $this->isLoading = false;
    }

    private function calculateIncomeStats()
    {
        $cacheKey = 'calculatePartnerIncomeStats_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $dateRange = [($this->year - 1).'-01-01', ($this->year - 1).'-12-31'];
        $lastPeriodeName = Carbon::parse(($this->year - 1).'-01-01')->format('Y');
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $lastPeriodeDate = Carbon::parse($this->year.'-'.$this->month.'-01')->subDay();
            $lastPeriodeName = $lastPeriodeDate->isoFormat('MMMM YYYY');
            $dateRange = [$lastPeriodeDate->format('Y-m').'-01', $lastPeriodeDate->format('Y-m-t')];
        }

        $transactionQuery = Transaction::withoutGlobalScope('forActiveBook')->where('in_out', 1)
            ->whereHas('partner', function ($query) {
                $query->whereJsonContains('type_code', $this->partnerTypeCode);
            });
        $transactionQuery->whereBetween('date', $dateRange);
        if ($this->book) {
            $transactionQuery->where('book_id', $this->book->id);
        }
        $amount = (float) $transactionQuery->sum('amount');

        $partnerIncomeStats['last_month_total'] = $amount;
        $partnerIncomeStats['last_month_name'] = $lastPeriodeName;

        $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        $currentYearName = Carbon::parse($this->year.'-01-01')->format('Y');

        $transactionQuery = Transaction::withoutGlobalScope('forActiveBook')->where('in_out', 1)
            ->whereHas('partner', function ($query) {
                $query->whereJsonContains('type_code', $this->partnerTypeCode);
            });

        $transactionQuery->whereBetween('date', $dateRange);
        if ($this->book) {
            $transactionQuery->where('book_id', $this->book->id);
        }
        $amount = (float) $transactionQuery->sum('amount');

        $partnerIncomeStats['current_year_total'] = $amount;
        $partnerIncomeStats['current_year_name'] = $currentYearName;

        Cache::put($cacheKey, $partnerIncomeStats, $duration);

        return $partnerIncomeStats;
    }
}
