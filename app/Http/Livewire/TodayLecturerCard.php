<?php

namespace App\Http\Livewire;

use App\Models\LecturingSchedule;
use Carbon\Carbon;
use Livewire\Component;

class TodayLecturerCard extends Component
{
    public $header;
    public $detailTextButton;
    public $intervalCarousel;
    public $lecturingSchedules = [];
    public $audienceCodes = [];
    public $linkDetailSchedule;
    public $isFriday;
    public $audienceFriday;

    public function getAudienceCodeList(): array
    {
        return [
            LecturingSchedule::AUDIENCE_FRIDAY => __('lecturing_schedule.audience_' . LecturingSchedule::AUDIENCE_FRIDAY),
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.audience_' . LecturingSchedule::AUDIENCE_PUBLIC),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.audience_' . LecturingSchedule::AUDIENCE_MUSLIMAH),
        ];
    }

    public function getHeader(): array
    {
        return [
            LecturingSchedule::AUDIENCE_FRIDAY => __('lecturing_schedule.friday_lecturing_schedule') . ' ' . __('time.today'),
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.lecturing_schedule') . ' ' . __('time.today'),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.lecturing_schedule') . ' ' . __('time.today')
        ];
    }

    public function today()
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('date', Carbon::today()->format('Y-m-d'));
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $this->lecturingSchedules = $lecturingScheduleQuery->get()
            ->filter(function ($lecturingSchedule) {
                if (
                    $lecturingSchedule->audience_code === LecturingSchedule::AUDIENCE_FRIDAY &&
                    !$this->isFriday
                ) {
                    return false;
                }
                return true;
            });
        $this->audienceCodes = $this->getAudienceCodeList();
    }

    public function mount()
    {
        $this->header = $this->getHeader();
        $this->detailTextButton = __('app.show');
        $this->intervalCarousel = "13000";
        $this->linkDetailSchedule = route('public_schedules.index');
        $this->isFriday = strtolower(Carbon::today()->format('l')) === LecturingSchedule::AUDIENCE_FRIDAY;
        $this->audienceFriday = LecturingSchedule::AUDIENCE_FRIDAY;
        $this->today();
    }

    public function render()
    {
        return view('livewire.today-lecturer-card');
    }
}
