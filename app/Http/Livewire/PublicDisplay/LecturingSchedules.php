<?php

namespace App\Http\Livewire\PublicDisplay;

use App\Models\Lecturing;
use Livewire\Component;

class LecturingSchedules extends Component
{
    public $lecturings = [];
    public $audienceFriday;
    public $theme;

    public function mount()
    {
        $this->lecturings = Lecturing::query()
            ->whereBetween('date', [
                today()->format('Y-m-d'),
                today()->addDays(config('public_display.lecturing_schedule_days', 7) - 1)->format('Y-m-d'),
            ])
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(config('public_display.lecturing_schedule_max_items', 5))
            ->get();
        $this->audienceFriday = Lecturing::AUDIENCE_FRIDAY;
    }

    public function render()
    {
        $view = 'livewire.public_display.themes.default.lecturing_schedules';
        if (view()->exists("livewire.public_display.themes.$this->theme.lecturing_schedules")) {
            $view = "livewire.public_display.themes.$this->theme.lecturing_schedules";
        }

        return view($view);
    }
}
