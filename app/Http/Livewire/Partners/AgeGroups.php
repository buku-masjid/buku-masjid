<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AgeGroups extends Component
{
    public $ageGroups;
    public $partnerTypeCode;
    public $partnerType;
    public $genders;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.partners.age_groups');
    }

    public function getAgeGroups()
    {
        $this->ageGroups = $this->calculateAgeGroups();
        $this->genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $this->isLoading = false;
    }

    private function calculateAgeGroups(): Collection
    {
        $rawSelect = 'COUNT(*) as partners_count, gender_code';
        $rawSelect .= ', CASE ';
        $rawSelect .= 'WHEN dob <= "1959-12-10" THEN "old"';
        $rawSelect .= 'WHEN dob BETWEEN "1959-12-10" AND "1984-12-10" THEN "mature"';
        $rawSelect .= 'WHEN dob BETWEEN "1984-12-10" AND "1999-12-10" THEN "young"';
        $rawSelect .= 'WHEN dob BETWEEN "1999-12-10" AND "2012-12-10" THEN "teenager"';
        $rawSelect .= 'WHEN dob >= "2012-12-10" THEN "kids"';
        $rawSelect .= ' END AS age_group_code';

        $partnersCountQuery = DB::table('partners')
            ->selectRaw($rawSelect)
            ->where('is_active', Partner::STATUS_ACTIVE)
            ->groupBy('gender_code', 'age_group_code');
        if ($this->partnerTypeCode) {
            $partnersCountQuery->whereJsonContains('type_code', $this->partnerTypeCode);
        }
        $partnersCount = $partnersCountQuery->get();

        return $partnersCount;
    }
}
