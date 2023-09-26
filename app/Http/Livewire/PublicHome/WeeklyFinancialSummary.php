<?php

namespace App\Http\Livewire\PublicHome;

use App\Transaction;
use Livewire\Component;

class WeeklyFinancialSummary extends Component
{
    public $currentBalance;
    public $currentWeekBalance;
    public $currentWeekIncome;
    public $currentWeekSpending;

    protected function getCurrentWeekTansactions()
    {
        $firstDayInWeek = now()->startOfWeek()->format('Y-m-d');
        $lastDayInWeek = now()->endOfWeek()->format('Y-m-d');
        $bookId = config('masjid.default_book_id');
        $transactionQuery = Transaction::query();
        $transactionQuery->where('date', '>=', $firstDayInWeek);
        $transactionQuery->where('date', '<=', $lastDayInWeek);
        $transactionQuery->where('book_id', $bookId);
        return $transactionQuery->orderBy('date', 'asc')->with('category', 'book')->get();
    }

    public function render()
    {
        return view('livewire.public_home.weekly_financial_summary');
    }

    public function mount()
    {
        $endOfLastWeek = now()->startOfWeek()->subDay()->format('Y-m-d');
        $lastWeekBalance = auth()->activeBook()->getBalance($endOfLastWeek);
        $this->getCurrentWeekTansactions()->each(function ($transaction) {
            if ($transaction->in_out) {
                $this->currentWeekIncome = $this->currentWeekIncome + $transaction->amount;
            } else {
                $this->currentWeekSpending = $this->currentWeekSpending + $transaction->amount;
            }
        });
        $this->currentWeekBalance = $lastWeekBalance;
        $this->currentBalance = $lastWeekBalance + $this->currentWeekIncome - $this->currentWeekSpending;
    }
}
