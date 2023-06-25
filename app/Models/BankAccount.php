<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'number', 'account_name', 'description', 'is_active', 'creator_id',
    ];

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? __('app.active') : __('app.inactive');
    }

    public function balances()
    {
        return $this->hasMany(BankAccountBalance::class);
    }

    public function lastBalance()
    {
        return $this->hasOne(BankAccountBalance::class)->ofMany([
            'date' => 'max',
        ]);
    }
}
