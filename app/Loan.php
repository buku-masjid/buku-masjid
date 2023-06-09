<?php

namespace App;

use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use ForUser;

    const TYPE_DEBT = 1;
    const TYPE_RECEIVABLE = 2;

    protected $fillable = [
        'partner_id', 'type_id', 'amount', 'description', 'creator_id',
        'start_date', 'end_date', 'planned_payment_count',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getTypeAttribute()
    {
        if ($this->type_id == Loan::TYPE_DEBT) {
            return __('loan.types.debt');
        }

        return __('loan.types.receivable');
    }

    public function getAmountStringAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPaymentTotalAttribute()
    {
        $paymentType = $this->type_id == static::TYPE_DEBT ? 0 : 1;

        return $this->transactions()->where('in_out', $paymentType)->sum('amount');
    }

    public function getPaymentTotalStringAttribute()
    {
        return number_format($this->payment_total, 2);
    }

    public function getPaymentRemainingAttribute()
    {
        return $this->amount - $this->payment_total;
    }

    public function getPaymentRemainingStringAttribute()
    {
        return number_format($this->payment_remaining, 2);
    }

    public function getTypeLabelAttribute()
    {
        $bgColor = $this->type_id == static::TYPE_DEBT ? '#00aabb' : '#bb004f';
        $type = $this->type;
        if ($this->end_date) {
            $type .= ' <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
        }

        return '<span class="badge" style="background-color: '.$bgColor.'">'.$type.'</span>';
    }

    public function delete()
    {
        $this->transactions()->update(['loan_id' => null]);

        return parent::delete();
    }
}
