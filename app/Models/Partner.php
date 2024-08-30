<?php

namespace App\Models;

use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'type_code', 'phone', 'work', 'address', 'description', 'is_active', 'creator_id',
    ];

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? __('app.active') : __('app.inactive');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTypeAttribute(): string
    {
        return $this->getAvailableTypes()[$this->type_code] ?? $this->type_code;
    }

    public function getAvailableTypes(): array
    {
        $partnerTypesConfig = config('partners.partner_types');
        if (!$partnerTypesConfig) {
            return ['partner' => __('partner.partner')];
        }
        $partnerTypes = [];
        // dump($partnerTypesConfig);
        $rawPartnerTypes = explode(',', $partnerTypesConfig);
        // dump($rawPartnerTypes);
        foreach ($rawPartnerTypes as $rawPartnerType) {
            $partnerType = explode('|', $rawPartnerType);
            // dd($partnerType);
            $partnerTypes[$partnerType[0]] = $partnerType[1];
        }
        // dd($partnerTypes);

        return $partnerTypes;
    }
}
