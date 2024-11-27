<?php

namespace App\Http\Livewire\Partners;

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
    public $partnerTypeCode;
    public $isLoading = true;

    public function render()
    {
        // $this->getIncomeDashboardEntries();
        // dump($this->year);
        return view('livewire.partners.income_dashboard');
    }

    public function getIncomeDashboardEntries()
    {
        $this->year = $this->year ?: Carbon::now()->format('Y');
        $this->incomeDashboardEntries = $this->calculateIncomeDashboardEntries();
        $this->availablePartners = Partner::whereIn('id', $this->incomeDashboardEntries->pluck('partner_id'))
            ->orderBy('name')
            ->get();
        $this->isLoading = false;
    }

    public function updated()
    {
        $this->incomeDashboardEntries = $this->calculateIncomeDashboardEntries();
    }

    private function calculateIncomeDashboardEntries(): Collection
    {
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
            ->whereBetween('t.date', [$this->year.'-01-01', $this->year.'-12-31'])
            ->selectRaw($rawSelect)
            ->groupBy('p.id', 'p.name', 'tr_year', 'tr_month', 'tr_year_month')
            ->having('total_amount', '>', 0)
            ->orderBy('p.name')
            ->get();

        // echo '<pre>$incomeEntries->toArray() : ', print_r($incomeEntries->toArray(), true), '</pre>';die();

        return $incomeEntries;
    }
}
