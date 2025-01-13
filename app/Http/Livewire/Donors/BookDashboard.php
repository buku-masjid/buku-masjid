<?php

namespace App\Http\Livewire\Donors;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BookDashboard extends Component
{
    public $bookDashboardEntries;
    public $availableBooks;
    public $year;
    public $month;
    public $book;
    public $bookTypeCode;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.book_dashboard');
    }

    public function getBookDashboardEntries()
    {
        $this->bookDashboardEntries = $this->calculateBookDashboardEntries()->groupBy('tr_year');
        $this->availableBooks = $this->getAvailableBooks($this->bookDashboardEntries);
        $this->isLoading = false;
    }

    private function calculateBookDashboardEntries(): Collection
    {
        $dateRange = [$this->year.'-01-01', $this->year.'-12-31'];
        if ($this->month != '00' && in_array($this->month, array_keys(get_months()))) {
            $dateRange = [$this->year.'-'.$this->month.'-01', Carbon::parse($this->year.'-'.$this->month.'-01')->format('Y-m-t')];
        }

        $rawSelect = 'b.id as book_id';
        $rawSelect .= ', b.name as book_name';
        $rawSelect .= ', YEAR(t.date) as tr_year';
        $rawSelect .= ', MONTH(t.date) as tr_month';
        $rawSelect .= ', CONCAT(YEAR(t.date), "-", LPAD(MONTH(t.date), 2, "0")) as tr_year_month';
        $rawSelect .= ', SUM(t.amount) as total_amount';

        $bookEntries = DB::table('books as b')
            ->join('transactions as t', 'b.id', '=', 't.book_id')
            ->join('partners as p', 'p.id', '=', 't.partner_id')
            ->where('t.in_out', 1)
            ->whereJsonContains('p.type_code', 'donatur')
            ->whereBetween('t.date', $dateRange)
            ->when($this->book, function ($query) {
                $query->where('t.book_id', $this->book->id);
            })
            ->selectRaw($rawSelect)
            ->groupBy('b.id', 'b.name', 'tr_year', 'tr_month', 'tr_year_month')
            ->having('total_amount', '>', 0)
            ->orderBy('b.name')
            ->get();

        return $bookEntries;
    }

    private function getAvailableBooks(Collection $bookDashboardEntries): array
    {
        $availableBooks = [];
        foreach ($bookDashboardEntries as $trYear => $bookEntries) {
            foreach ($bookEntries as $bookEntry) {
                $availableBooks[$trYear][$bookEntry->book_id] = (object) [
                    'id' => $bookEntry->book_id,
                    'name' => $bookEntry->book_name,
                ];
            }
        }

        return $availableBooks;
    }
}
