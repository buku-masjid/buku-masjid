<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkTypes extends Component
{
    public $workTypes;
    public $partnerTypeCode;
    public $partnerType;
    public $genders;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.work_types');
    }

    public function getWorkTypes()
    {
        $this->workTypes = $this->calculateWorkTypes();
        $this->genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $this->isLoading = false;
    }

    private function calculateWorkTypes(): Collection
    {
        $partnersCountQuery = DB::table('partners')
            ->selectRaw('COUNT(*) as partners_count, gender_code, work_type_id')
            ->where('is_active', Partner::STATUS_ACTIVE)
            ->groupBy('gender_code', 'work_type_id');
        if ($this->partnerTypeCode) {
            $partnersCountQuery->whereJsonContains('type_code', $this->partnerTypeCode);
        }
        $partnersCount = $partnersCountQuery->get();

        return $partnersCount;
    }
}
