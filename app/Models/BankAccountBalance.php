<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountBalance extends Model
{
    protected $fillable = ['bank_account_id', 'date', 'amount', 'description', 'creator_id'];
}
