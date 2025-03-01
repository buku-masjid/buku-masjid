<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class NextWeekButton extends Component
{
    public $routeName = 'reports.finance.summary';
    public $startDate = null;
    public $endDate = null;
    public $buttonText = '';
    public $buttonClass = 'btn btn-secondary';

    public function mount()
    {
        $book = auth()->activeBook();

        $startDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));
        $startDate = Carbon::now()->startOfWeek($startDayInteger)->addWeek()->format('Y-m-d');

        if (request('start_date')) {
            $startDate = Carbon::parse(request('start_date'))->addWeek()->format('Y-m-d');
        }

        $endDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));
        if (strtolower(Carbon::now()->format('l')) == $book->start_week_day_code) {
            $endDate = Carbon::now()->addDay()->endOfWeek($endDayInteger)->subDay()->addWeek()->format('Y-m-d');
        } else {
            $endDate = Carbon::now()->endOfWeek($endDayInteger)->subDay()->addWeek()->format('Y-m-d');
        }

        if (request('end_date')) {
            $endDate = Carbon::parse(request('end_date'))->addWeek()->format('Y-m-d');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->buttonText = $this->buttonText ?: __('report.next_week');
    }

    public function render()
    {
        return view('livewire.prev_next_week_button');
    }
}
