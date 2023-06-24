<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccountBalance extends Model
{
    protected $fillable = ['bank_account_id', 'date', 'amount', 'description', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'n/a']);
    }
    public function getAmountStringAttribute()
    {
        return number_format($this->amount, 2);
    }
}
