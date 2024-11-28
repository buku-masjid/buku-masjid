<?php

namespace App\Http\Livewire\Donors;

use App\Models\Partner;
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
        $incomeDashboardEntries = $this->calculateIncomeDashboardEntries();
        $this->incomeDashboardEntries = $incomeDashboardEntries->groupBy('tr_year');
        $this->availablePartners = Partner::whereIn('id', $incomeDashboardEntries->pluck('partner_id'))
            ->orderBy('name')
            ->get();
        $this->isLoading = false;
    }

    private function calculateIncomeDashboardEntries(): Collection
    {
        $dateRange = [];
        if ($this->year != '0000') {
            $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
            if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
                $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
            }
        }

        $rawSelect = 'p.id as partner_id';
        $rawSelect .= ', p.name as partner_name';
        $rawSelect .= ', YEAR(t.date) as tr_year';
        $rawSelect .= ', MONTH(t.date) as tr_month';
        $rawSelect .= ', CONCAT(YEAR(t.date), "-", LPAD(MONTH(t.date), 2, "0")) as tr_year_month';
        $rawSelect .= ', SUM(t.amount) as total_amount';

        $incomeEntries = DB::table('partners as p')
            ->join('transactions as t', 'p.id', '=', 't.partner_id')
            ->where('t.in_out', 1)
            ->where('p.type_code', 'donatur')
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('t.date', $dateRange);
            })
            ->selectRaw($rawSelect)
            ->groupBy('p.id', 'p.name', 'tr_year', 'tr_month', 'tr_year_month')
            ->having('total_amount', '>', 0)
            ->orderBy('p.name')
            ->get();

        // echo '<pre>$incomeEntries->toArray() : ', print_r($incomeEntries->toArray(), true), '</pre>';die();

        return $incomeEntries;
    }
}
