<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\Lecturing;
use Livewire\Component;

class DailyLecturings extends Component
{
    public $date;
    public $dayTitle;
    public $audienceFriday;
    public $lecturerName;
    public $lecturings = [];

    public function getLecturerName(): array
    {
        return [
            Lecturing::AUDIENCE_FRIDAY => __('lecturing.friday_lecturer_name'),
            Lecturing::AUDIENCE_PUBLIC => __('lecturing.lecturer_name'),
            Lecturing::AUDIENCE_MUSLIMAH => __('lecturing.lecturer_name'),
            Lecturing::AUDIENCE_TARAWIH => __('lecturing.lecturer_name'),
        ];
    }

    public function mount()
    {
        $lecturingQuery = Lecturing::query();
        $lecturingQuery->where('date', $this->date->format('Y-m-d'));
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $this->lecturings = $lecturingQuery->get();
        $this->lecturerName = $this->getLecturerName();
        $this->audienceFriday = Lecturing::AUDIENCE_FRIDAY;
        $this->audienceTarawih = Lecturing::AUDIENCE_TARAWIH;
    }

    public function render()
    {
        return view('livewire.public_home.daily_lecturings');
    }
}
