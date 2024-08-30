<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'phone', 'work', 'address', 'description', 'is_active', 'creator_id',
    ];

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? __('app.active') : __('app.inactive');
    }
}
