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
        $yearMonth = request('year', date('Y')).'-'.request('month', date('m'));
        $nextMonthDate = Carbon::parse($yearMonth.'-10')->addMonth();

        $this->month = $nextMonthDate->format('m');
        $this->year = $nextMonthDate->format('Y');
        $this->buttonText = __('report.next_month');
    }

    public function render()
    {
        return view('livewire.prev_next_month_button');
    }
}
