<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MaritalStatuses extends Component
{
    public $maritalStatuses;
    public $partnerTypeCode;
    public $partnerType;
    public $genders;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.marital_statuses');
    }

    public function getMaritalStatuses()
    {
        $this->maritalStatuses = $this->calculateMaritalStatuses();
        $this->genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $this->isLoading = false;
    }

    private function calculateMaritalStatuses(): Collection
    {
        $partnersCountQuery = DB::table('partners')
            ->selectRaw('COUNT(*) as partners_count, gender_code, marital_status_id')
            ->where('is_active', Partner::STATUS_ACTIVE)
            ->groupBy('gender_code', 'marital_status_id');
        if ($this->partnerTypeCode) {
            $partnersCountQuery->whereJsonContains('type_code', $this->partnerTypeCode);
        }
        $partnersCount = $partnersCountQuery->get();

        return $partnersCount;
    }
}
