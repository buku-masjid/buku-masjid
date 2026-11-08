<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class NextMonthButton extends Component
{
    public $routeName = 'reports.index';
    public $monthNumber = null;
    public $yearNumber = null;
    public $buttonText = '';
    public $buttonClass = 'btn btn-secondary';
    public $isDisabled = false;

    public function mount()
    {
        $validYearMonth = parse_year_month_input(request('year', date('Y')), request('month', date('m')));

        if (is_null($validYearMonth)) {
            $nextMonthDate = Carbon::now()->startOfMonth()->addMonth();
        } else {
            [$year, $month] = $validYearMonth;
            if ($month == '00') {
                $month = Carbon::now()->format('m');
            }
            $nextMonthDate = Carbon::parse($year.'-'.$month.'-10')->addMonth();
        }

        $this->month = $nextMonthDate->format('m');
        $this->year = $nextMonthDate->format('Y');
        $this->buttonText = $this->buttonText ?: __('report.next_month');
        $this->isDisabled = $nextMonthDate->copy()->endOfMonth()->gt(get_report_date_bounds(auth()->activeBook())[1]);
    }

    public function render()
    {
        return view('livewire.prev_next_month_button');
    }
}
