<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class PrevMonthButton extends Component
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
            $prevMonthDate = Carbon::now()->startOfMonth()->subMonth();
        } else {
            [$year, $month] = $validYearMonth;
            if ($month == '00') {
                $month = Carbon::now()->format('m');
            }
            $prevMonthDate = Carbon::parse($year.'-'.$month.'-10')->subMonth();
        }

        $this->month = $prevMonthDate->format('m');
        $this->year = $prevMonthDate->format('Y');
        $this->buttonText = $this->buttonText ?: __('report.prev_month');
        $this->isDisabled = $prevMonthDate->copy()->startOfMonth()->lt(get_report_date_bounds(auth()->activeBook())[0]);
    }

    public function render()
    {
        return view('livewire.prev_next_month_button');
    }
}
