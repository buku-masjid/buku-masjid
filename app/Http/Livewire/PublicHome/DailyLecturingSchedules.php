<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\LecturingSchedule;
use Livewire\Component;

class DailyLecturingSchedules extends Component
{
    public $date;
    public $dayTitle;
    public $audienceFriday;
    public $lecturerName;
    public $lecturingSchedules = [];

    public function getLecturerName(): array
    {
        return [
            LecturingSchedule::AUDIENCE_FRIDAY => __('lecturing_schedule.friday_lecturer_name'),
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.lecturer_name'),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.lecturer_name'),
        ];
    }

    public function mount()
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('date', $this->date->format('Y-m-d'));
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $this->lecturingSchedules = $lecturingScheduleQuery->get();
        $this->lecturerName = $this->getLecturerName();
        $this->audienceFriday = LecturingSchedule::AUDIENCE_FRIDAY;
    }

    public function render()
    {
        return view('livewire.public_home.daily_lecturing_schedules');
    }
}
