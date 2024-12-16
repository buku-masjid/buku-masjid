<?php

namespace App\Models;

use App\EloquentFilters\PartnersFilter;
use App\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Partner extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';
    const SEARCH_KEYS = ['name', 'phone', 'address'];

    protected $fillable = [
        'name', 'type_code', 'level_code', 'phone', 'work', 'address', 'description', 'is_active', 'creator_id',
        'gender_code', 'pob', 'dob', 'work_type_id', 'marital_status_id', 'financial_status_id', 'activity_status_id',
        'religion_id', 'rt', 'rw',
    ];

    public $casts = [
        'type_code' => 'array',
        'level_code' => 'array',
    ];

    public function scopeFilterBy(Builder $queryBuilder, Request $request)
    {
        return (new PartnersFilter($queryBuilder))->apply($request);
    }

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? __('app.active') : __('app.inactive');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->withoutGlobalScope('forActiveBook');
    }

    public function getGenderAttribute(): ?string
    {
        if ($this->gender_code == 'm') {
            return __('app.gender_male');
        }
        if ($this->gender_code == 'f') {
            return __('app.gender_female');
        }

        return $this->gender_code;
    }

    public function getTypeAttribute(): string
    {
        $typeCodes = [];
        foreach ($this->type_code as $typeCode) {
            $typeCodes[] = $this->getAvailableTypes()[$typeCode] ?? $typeCode;
        }

        return implode(', ', $typeCodes);
    }

    public function getLevelAttribute(): ?string
    {
        if (!$this->level_code) {
            return null;
        }

        $levelCodes = [];
        foreach ($this->level_code as $typeCode => $levelCode) {
            $availableLevelCodes = collect($this->getAvailableLevels([$typeCode]))->first();
            $levelCodes[] = $availableLevelCodes[$levelCode] ?? $levelCode;
        }

        return implode(', ', $levelCodes);
    }

    public function getAvailableTypes(): array
    {
        $partnerTypesConfig = config('partners.partner_types');
        if (!$partnerTypesConfig) {
            return ['partner' => __('partner.partner')];
        }
        $partnerTypes = [];
        $rawPartnerTypes = explode(',', $partnerTypesConfig);
        foreach ($rawPartnerTypes as $rawPartnerType) {
            $partnerType = explode('|', $rawPartnerType);
            $partnerTypes[$partnerType[0]] = $partnerType[1];
        }

        return $partnerTypes;
    }

    public function getAvailableLevels(array $typeCodes): array
    {
        $partnerLevelsConfig = config('partners.partner_levels');
        if (!$partnerLevelsConfig) {
            return [];
        }
        $partnerLevels = [];
        $rawPartnerLevels = explode(',', $partnerLevelsConfig);
        foreach ($typeCodes as $typeCode) {
            $typeName = $this->getAvailableTypes()[$typeCode] ?? null;
            foreach ($rawPartnerLevels as $rawPartnerLevelArray) {
                $rawPartnerLevel = explode(':', $rawPartnerLevelArray);
                $partnerLevelCode = $rawPartnerLevel[0];
                if ($partnerLevelCode != $typeCode) {
                    continue;
                }
                $singlePartnerLevels = [];
                $partnerLevelCodeNames = explode('|', $rawPartnerLevel[1]);
                foreach ($partnerLevelCodeNames as $key => $partnerLevelCodeName) {
                    if ($key % 2 == 0) {
                        $singlePartnerLevels[$partnerLevelCodeNames[$key]] = $partnerLevelCodeNames[$key + 1];
                    }
                }
                $key = $typeName ?: $typeCode;
                $partnerLevels[$key] = $singlePartnerLevels;
            }
        }

        return $partnerLevels;
    }

    public function getNamePhoneAttribute(): string
    {
        if ($this->phone) {
            return $this->name.' ('.$this->phone.')';
        }

        return $this->name;
    }

    public function getWorkTypeAttribute()
    {
        return __('partner.work_types')[$this->work_type_id] ?? __('app.unknown');
    }

    public function getReligionAttribute()
    {
        return __('partner.religions')[$this->religion_id] ?? __('app.unknown');
    }

    public function getMaritalStatusAttribute()
    {
        return __('partner.marital_statuses')[$this->marital_status_id] ?? __('app.unknown');
    }

    public function getFinancialStatusAttribute()
    {
        return __('partner.financial_statuses')[$this->financial_status_id] ?? __('app.unknown');
    }

    public function getActivityStatusAttribute()
    {
        return __('partner.activity_statuses')[$this->activity_status_id] ?? __('app.unknown');
    }
}
