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

    public function mount()
    {
        $yearMonth = request('year', date('Y')).'-'.request('month', date('m'));
        $prevMonthDate = Carbon::parse($yearMonth.'-10')->subMonth();

        $this->month = $prevMonthDate->format('m');
        $this->year = $prevMonthDate->format('Y');
        $this->buttonText = __('report.prev_month');
    }

    public function render()
    {
        return view('livewire.prev_next_month_button');
    }
}
