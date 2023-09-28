<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\Book;
use App\Transaction;
use Livewire\Component;

class WeeklyFinancialSummary extends Component
{
    public $currentBalance = 0;
    public $startWeekBalance = 0;
    public $currentWeekIncomeTotal = 0;
    public $currentWeekSpendingTotal = 0;

    public function render()
    {
        return view('livewire.public_home.weekly_financial_summary');
    }

    public function mount()
    {
        $defaultBook = Book::find(config('masjid.default_book_id'));
        if (is_null($defaultBook)) {
            return;
        }
        $startDate = now()->startOfWeek()->format('Y-m-d');
        $endDate = now()->endOfWeek()->format('Y-m-d');
        $currentWeekTransactions = $defaultBook->transactions()->whereBetween('date', [$startDate, $endDate])->get();
        $this->currentWeekIncomeTotal = $currentWeekTransactions->where('in_out', 1)->sum('amount');
        $this->currentWeekSpendingTotal = $currentWeekTransactions->where('in_out', 0)->sum('amount');
        $endOfLastWeekDate = now()->startOfWeek()->subDay()->format('Y-m-d');
        $this->startWeekBalance = $defaultBook->getBalance($endOfLastWeekDate);
        $this->currentBalance = $this->startWeekBalance + $this->currentWeekIncomeTotal - $this->currentWeekSpendingTotal;
    }
}
