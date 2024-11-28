<?php

namespace App\Http\Livewire\Donors;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IncomeFromPartnerGraph extends Component
{
    public $incomeFromPartnerSeries;
    public $book;
    public $month;
    public $year;
    public $partnerTypeCode = 'donatur';
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.income_from_partner_graph');
    }

    public function getIncomeFromPartnerSeries()
    {
        $this->incomeFromPartnerSeries = $this->calculateIncomeFromPartnerSeries();
        $this->isLoading = false;
    }

    private function calculateIncomeFromPartnerSeries()
    {
        $cacheKey = 'calculatePartnerIncomeFromPartnerSeries_'.$this->partnerTypeCode.'_'.$this->year.'_'.$this->month.'_'.optional($this->book)->id;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $partnerMonthlySummary = $this->calculatePartnerMonthlySummary($this->partnerTypeCode, 1);
        $availableYears = $partnerMonthlySummary->pluck('transaction_year')->unique();
        $series = [];
        foreach ($availableYears as $year) {
            $serie = [
                'name' => $year,
                'type' => 'line',
                'data' => [],
            ];
            foreach (get_months() as $monthNumber => $monthName) {
                $monthlySummary = $partnerMonthlySummary->filter(function ($monthlySummary) use ($year, $monthNumber) {
                    return $monthlySummary->transaction_year == $year && $monthlySummary->transaction_month == (int) $monthNumber;
                })->first();
                $serie['data'][] = $monthlySummary ? (float) $monthlySummary->total : 0;
            }
            $series[] = $serie;
        }

        Cache::put($cacheKey, $series, $duration);

        return $series;
    }

    private function calculatePartnerMonthlySummary(string $partnerType, int $inOut): Collection
    {
        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
            if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
                $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            }
        }

        $rawSelect = 'count(id) as transactions_count';
        $rawSelect .= ', sum(amount) as total';
        $rawSelect .= ', year(date) as transaction_year';
        $rawSelect .= ', month(date) as transaction_month';
        $partnerMonthlySummary = DB::table('transactions')->selectRaw($rawSelect)
            ->whereExists(function (Builder $query) use ($partnerType) {
                $query->select(DB::raw(1))
                    ->from('partners')
                    ->whereColumn('transactions.partner_id', 'partners.id')
                    ->where('partners.type_code', $partnerType);
            })
            ->when($this->book, function ($query) {
                $query->where('book_id', $this->book->id);
            })
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('date', $dateRange);
            })
            ->where('in_out', $inOut)
            ->groupBy('transaction_year')
            ->groupBy('transaction_month')
            ->get();

        return $partnerMonthlySummary;
    }
}
