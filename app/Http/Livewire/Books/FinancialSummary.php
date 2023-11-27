<?php

namespace App\Http\Livewire\Books;

use App\Models\Book;
use Livewire\Component;

class FinancialSummary extends Component
{
    public $bookId;
    public $start;
    public $todayDayDate;
    public $currentBudget = 0;
    public $currentBalance = 0;
    public $startBalance = 0;
    public $currentIncomeTotal = 0;
    public $currentSpendingTotal = 0;
    public $reportPeriodeCode = Book::REPORT_PERIODE_IN_MONTHS;

    public function render()
    {
        return view('livewire.books.financial_summary');
    }

    public function mount()
    {
        $this->today = today();
        $book = Book::find($this->bookId);
        if (is_null($book)) {
            return;
        }
        $this->start = today()->startOfWeek();
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            $this->start = today()->startOfMonth();
        }
        $transactionQuery = $book->transactions()
            ->withoutGlobalScope('forActiveBook');
        if ($book->report_periode_code != Book::REPORT_PERIODE_ALL_TIME) {
            $transactionQuery->whereBetween('date', [$this->start->format('Y-m-d'), $this->today->format('Y-m-d')]);
        }
        $currentTransactions = $transactionQuery->get();
        $this->currentBudget = $book->budget;
        $endOfLastDate = today()->startOfWeek()->subDay()->format('Y-m-d');
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            $endOfLastDate = today()->startOfMonth()->subDay()->format('Y-m-d');
        }
        $this->startBalance = ($book->report_periode_code == Book::REPORT_PERIODE_ALL_TIME) ? 0 : $book->getBalance($endOfLastDate);
        $this->currentSpendingTotal = $currentTransactions->where('in_out', 0)->sum('amount');
        if ($book->budget) {
            $this->currentIncomeTotal = $currentTransactions->where('in_out', 1)->sum('amount') + $this->startBalance;
            $this->currentBalance = $this->currentIncomeTotal - $this->currentSpendingTotal;
        } else {
            $this->currentIncomeTotal = $currentTransactions->where('in_out', 1)->sum('amount');
            $this->currentBalance = $this->startBalance + $this->currentIncomeTotal - $this->currentSpendingTotal;
        }
        $this->budgetDifference = $this->currentBudget - $this->currentIncomeTotal;
        $this->currentPeriodeBudgetLabel = __('report.current_periode_budget');
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            $this->currentPeriodeBudgetLabel = __('report.current_month_budget');
        }
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_WEEKS) {
            $this->currentPeriodeBudgetLabel = __('report.current_week_budget');
        }
        $this->reportPeriodeCode = $book->report_periode_code;
    }
}
