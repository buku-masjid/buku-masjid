<?php

namespace App\Http\Livewire\PublicDisplay;

use App\Models\Book;
use Livewire\Component;

class FinancialSummary extends Component
{
    public $bookName;
    public $startWeek;
    public $todayDayDate;
    public $bookVisibility = 'public';
    public $currentBalance = 0;
    public $startWeekBalance = 0;
    public $currentWeekIncomeTotal = 0;
    public $currentWeekSpendingTotal = 0;
    public $theme;

    public function render()
    {
        $view = 'livewire.public_display.themes.default.financial_summary';
        if (view()->exists("livewire.public_display.themes.$this->theme.financial_summary")) {
            $view = "livewire.public_display.themes.$this->theme.financial_summary";
        }

        return view($view);
    }

    public function mount()
    {
        $startWeek = today()->startOfWeek();
        $this->startWeek = $startWeek->copy();
        $this->today = today();
        $defaultBook = Book::find(config('masjid.default_book_id'));
        if (is_null($defaultBook)) {
            return;
        }
        if ($defaultBook->start_week_day_code != 'monday') {
            $startWeek = today()->previous(constant('Carbon\\Carbon::'.strtoupper($defaultBook->start_week_day_code)));
            $this->startWeek = $startWeek->copy();
        }
        $this->bookVisibility = $defaultBook->report_visibility_code;
        $currentWeekTransactions = $defaultBook->transactions()
            ->whereBetween('date', [$this->startWeek->format('Y-m-d'), $this->today->format('Y-m-d')])->get();
        $this->currentWeekIncomeTotal = $currentWeekTransactions->where('in_out', 1)->sum('amount');
        $this->currentWeekSpendingTotal = $currentWeekTransactions->where('in_out', 0)->sum('amount');
        $endOfLastWeekDate = $startWeek->subDay()->format('Y-m-d');
        $this->startWeekBalance = $defaultBook->getBalance($endOfLastWeekDate);
        $this->bookName = $defaultBook->name;
        $this->currentBalance = $this->startWeekBalance + $this->currentWeekIncomeTotal - $this->currentWeekSpendingTotal;
    }
}
