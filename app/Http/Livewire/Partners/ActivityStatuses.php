<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ActivityStatuses extends Component
{
    public $activityStatuses;
    public $partnerTypeCode;
    public $partnerType;
    public $genders;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.activity_statuses');
    }

    public function getActivityStatuses()
    {
        $this->activityStatuses = $this->calculateActivityStatuses();
        $this->genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $this->isLoading = false;
    }

    private function calculateActivityStatuses(): Collection
    {
        $partnersCountQuery = DB::table('partners')
            ->selectRaw('COUNT(*) as partners_count, gender_code, activity_status_id')
            ->where('is_active', Partner::STATUS_ACTIVE)
            ->groupBy('gender_code', 'activity_status_id');
        if ($this->partnerTypeCode) {
            $partnersCountQuery->whereJsonContains('type_code', $this->partnerTypeCode);
        }
        $partnersCount = $partnersCountQuery->get();

        return $partnersCount;
    }
}
