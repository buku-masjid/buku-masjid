<?php

namespace App\Http\Livewire\Donors;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IncomeDashboard extends Component
{
    public $incomeDashboardEntries;
    public $availablePartners;
    public $year;
    public $month;
    public $book;
    public $partnerTypeCode;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.income_dashboard');
    }

    public function getIncomeDashboardEntries()
    {
        $this->incomeDashboardEntries = $this->calculateIncomeDashboardEntries()->groupBy('tr_year');
        $this->availablePartners = $this->getAvailablePartners($this->incomeDashboardEntries);
        $this->isLoading = false;
    }

    private function calculateIncomeDashboardEntries(): Collection
    {
        $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
        }

        $rawSelect = 'p.id as partner_id';
        $rawSelect .= ', p.name as partner_name';
        $rawSelect .= ', p.phone as partner_phone';
        $rawSelect .= ', YEAR(t.date) as tr_year';
        $rawSelect .= ', MONTH(t.date) as tr_month';
        $rawSelect .= ', CONCAT(YEAR(t.date), "-", LPAD(MONTH(t.date), 2, "0")) as tr_year_month';
        $rawSelect .= ', SUM(t.amount) as total_amount';

        $incomeEntries = DB::table('partners as p')
            ->join('transactions as t', 'p.id', '=', 't.partner_id')
            ->where('t.in_out', 1)
            ->whereJsonContains('p.type_code', 'donatur')
            ->whereBetween('t.date', $dateRange)
            ->when($this->book, function ($query) {
                $query->where('t.book_id', $this->book->id);
            })
            ->selectRaw($rawSelect)
            ->groupBy('p.id', 'p.name', 'p.phone', 'tr_year', 'tr_month', 'tr_year_month')
            ->having('total_amount', '>', 0)
            ->orderBy('p.name')
            ->get();

        return $incomeEntries;
    }

    private function getAvailablePartners(Collection $incomeDashboardEntries): array
    {
        $availablePartners = [];
        foreach ($incomeDashboardEntries as $trYear => $incomeEntries) {
            foreach ($incomeEntries as $incomeEntry) {
                $availablePartners[$trYear][$incomeEntry->partner_id] = (object) [
                    'id' => $incomeEntry->partner_id,
                    'name' => $incomeEntry->partner_name,
                    'phone' => $incomeEntry->partner_phone,
                ];
            }
        }

        return $availablePartners;
    }
}
