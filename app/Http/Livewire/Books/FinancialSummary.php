<?php

namespace App\Http\Livewire\Books;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FinancialSummary extends Component
{
    public $bookId;
    public $start;
    public $isAlltime = true;
    public $currentBudget = 0;
    public $currentBalance = 0;
    public $startBalance = 0;
    public $currentIncomeTotal = 0;
    public $currentSpendingTotal = 0;
    public $currentPeriodeBudgetLabel;
    public $budgetDifference = 0;
    public $budgetDifferenceColorClass;

    public function render()
    {
        return view('livewire.books.financial_summary');
    }

    public function mount()
    {
        $book = Book::find($this->bookId);
        if (is_null($book)) {
            return;
        }

        if ($book->report_periode_code != Book::REPORT_PERIODE_ALL_TIME) {
            $this->startBalance = $this->getStartBalance($book);
            $this->start = $this->getStartDate($book);
            $this->isAlltime = false;
        }
        $this->today = today();
        $currentTransactions = $this->getBookCurrentTransactions($book, $this->start, $this->today);
        $this->currentSpendingTotal = $currentTransactions->where('in_out', 0)->sum('amount');
        $this->currentIncomeTotal = $currentTransactions->where('in_out', 1)->sum('amount');
        $this->currentBalance = $this->startBalance + $this->currentIncomeTotal - $this->currentSpendingTotal;

        if (!$book->budget) {
            return;
        }

        $this->currentBudget = $book->budget;
        $this->currentIncomeTotal += $this->startBalance;
        $this->currentBalance -= $this->startBalance;
        $this->budgetDifference = $book->budget - $this->currentIncomeTotal;
        $this->currentPeriodeBudgetLabel = $this->setCurrentPeriodeBudgetLabel($book);

        $this->budgetDifferenceColorClass = 'text-red';
        $this->currentBudgetRemainingLabel = __('report.current_periode_budget_remaining');

        if ($this->budgetDifference < 0) {
            $this->budgetDifferenceColorClass = 'text-success';
            $this->currentBudgetRemainingLabel = __('report.current_periode_budget_excess');
        }
    }

    private function getStartDate(Book $book): Carbon
    {
        return $book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS ? today()->startOfMonth() : today()->startOfWeek();
    }

    public function getStartBalance(Book $book): float
    {
        $endOfLastDate = today()->startOfWeek()->subDay()->format('Y-m-d');

        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            $endOfLastDate = today()->startOfMonth()->subDay()->format('Y-m-d');
        }

        return $book->getBalance($endOfLastDate);
    }

    private function getBookCurrentTransactions(Book $book, ?Carbon $startDate, Carbon $endDate): Collection
    {
        $transactionQuery = $book->transactions()->withoutGlobalScope('forActiveBook');
        if (!is_null($startDate)) {
            $transactionQuery->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }

        return $transactionQuery->get();
    }

    private function setCurrentPeriodeBudgetLabel(Book $book): string
    {
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            return __('report.current_month_budget');
        }
        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_WEEKS) {
            return __('report.current_week_budget');
        }

        return __('report.current_periode_budget');
    }
}
