<?php

namespace App\EloquentFilters;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnersFilter extends EloquentFilter
{
    public function apply(Request $request)
    {
        $this->filterBySearchQuery($request->get('search_query'), Partner::SEARCH_KEYS);
        $this->filterByTypeCode($request->get('type_code'));
        $this->filterByGenderCode($request->get('gender_code'));
        $this->filterByLevelCode($request->get('type_code'), $request->get('level_code'));
        $this->filterByAgeGroupCode($request->get('age_group_code'));
        $this->filterByWorkTypeId($request->get('work_type_id'));
        $this->filterByMaritalStatusId($request->get('marital_status_id'));
        $this->filterByFinancialStatusId($request->get('financial_status_id'));
        $this->filterByActivityStatusId($request->get('activity_status_id'));
        $this->filterByReligionId($request->get('religion_id'));
        $this->filterByActiveStatusId($request->get('is_active'));

        return $this->queryBuilder;
    }

    private function filterByTypeCode($typeCode)
    {
        if ($typeCode) {
            $this->queryBuilder->whereJsonContains('type_code', $typeCode);
        }
    }

    private function filterByGenderCode($genderCode)
    {
        if ($genderCode) {
            $this->queryBuilder->where('gender_code', $genderCode);
        }
    }

    private function filterByLevelCode($partnerCode, $levelCode)
    {
        if ($levelCode) {
            $this->queryBuilder->whereJsonContains('level_code', [$partnerCode => $levelCode]);
        }
    }

    private function filterByAgeGroupCode($ageGroupCode)
    {
        if ($ageGroupCode) {
            if ($ageGroupCode == 'null') {
                $this->queryBuilder->whereNull('dob');
            } else {
                $ageGroups = get_age_group_date_ranges();
                $dateRange = isset($ageGroups[$ageGroupCode]) ? $ageGroups[$ageGroupCode] : [];
                if ($dateRange) {
                    if (in_array($dateRange[1], ['<=', '>='])) {
                        $this->queryBuilder->where('dob', $dateRange[1], $dateRange[0]);
                    } else {
                        $this->queryBuilder->whereBetween('dob', $dateRange);
                    }
                }
            }
        }
    }

    private function filterByWorkTypeId($workTypeId)
    {
        if ($workTypeId) {
            if ($workTypeId == 'null') {
                $this->queryBuilder->whereNull('work_type_id');
            } else {
                $this->queryBuilder->where('work_type_id', $workTypeId);
            }
        }
    }

    private function filterByMaritalStatusId($maritalStatusId)
    {
        if ($maritalStatusId) {
            if ($maritalStatusId == 'null') {
                $this->queryBuilder->whereNull('marital_status_id');
            } else {
                $this->queryBuilder->where('marital_status_id', $maritalStatusId);
            }
        }
    }

    private function filterByFinancialStatusId($financialStatusId)
    {
        if ($financialStatusId) {
            if ($financialStatusId == 'null') {
                $this->queryBuilder->whereNull('financial_status_id');
            } else {
                $this->queryBuilder->where('financial_status_id', $financialStatusId);
            }
        }
    }

    private function filterByActivityStatusId($activityStatusId)
    {
        if ($activityStatusId) {
            if ($activityStatusId == 'null') {
                $this->queryBuilder->whereNull('activity_status_id');
            } else {
                $this->queryBuilder->where('activity_status_id', $activityStatusId);
            }
        }
    }

    private function filterByReligionId($religionId)
    {
        if ($religionId) {
            if ($religionId == 'null') {
                $this->queryBuilder->whereNull('religion_id');
            } else {
                $this->queryBuilder->where('religion_id', $religionId);
            }
        }
    }

    private function filterByActiveStatusId($activeStatusId)
    {
        if (!is_null($activeStatusId)) {
            $this->queryBuilder->where('is_active', $activeStatusId);
        }
    }
}
