<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\LecturingSchedule;
use Carbon\Carbon;
use Livewire\Component;

class TodayLecturingSchedules extends Component
{
    public $header;
    public $detailTextButton;
    public $intervalCarousel;
    public $lecturingSchedules = [];
    public $audienceCodes = [];
    public $linkDetailSchedule;
    public $isFriday;
    public $audienceFriday;
    public $lecturerName;

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

    public function getLecturerName(): array
    {
        return [
            LecturingSchedule::AUDIENCE_FRIDAY => __('lecturing_schedule.friday_lecturer_name'),
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.lecturer_name'),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.lecturer_name')
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
        $this->lecturerName = $this->getLecturerName();
    }

    public function mount()
    {
        $this->header = $this->getHeader();
        $this->detailTextButton = __('app.show');
        $this->intervalCarousel = "5000";
        $this->linkDetailSchedule = route('public_schedules.index');
        $this->isFriday = strtolower(Carbon::today()->format('l')) === LecturingSchedule::AUDIENCE_FRIDAY;
        $this->audienceFriday = LecturingSchedule::AUDIENCE_FRIDAY;
        $this->today();
    }

    public function render()
    {
        return view('livewire.public_home.today_lecturing_schedules');
    }
}
