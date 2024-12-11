<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Religions extends Component
{
    public $religions;
    public $partnerTypeCode;
    public $partnerType;
    public $genders;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.religions');
    }

    public function getReligions()
    {
        $this->religions = $this->calculateReligions();
        $this->genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $this->isLoading = false;
    }

    private function calculateReligions(): Collection
    {
        $partnersCountQuery = DB::table('partners')
            ->selectRaw('COUNT(*) as partners_count, gender_code, religion_id')
            ->where('is_active', Partner::STATUS_ACTIVE)
            ->groupBy('gender_code', 'religion_id');
        if ($this->partnerTypeCode) {
            $partnersCountQuery->whereJsonContains('type_code', $this->partnerTypeCode);
        }
        $partnersCount = $partnersCountQuery->get();

        return $partnersCount;
    }
}
