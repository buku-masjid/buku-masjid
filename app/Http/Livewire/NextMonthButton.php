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

    public function mount()
    {
        $month = request('month', date('m'));
        if (!isset(get_months()[$month])) {
            $month = Carbon::now()->format('m');
        }
        $year = (int) request('year', date('Y'));
        $yearMonth = $year.'-'.$month;
        $nextMonthDate = Carbon::parse($yearMonth.'-10')->addMonth();

        $this->month = $nextMonthDate->format('m');
        $this->year = $nextMonthDate->format('Y');
        $this->buttonText = $this->buttonText ?: __('report.next_month');
    }

    public function render()
    {
        return view('livewire.prev_next_month_button');
    }
}
