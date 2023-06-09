<?php

namespace App;

use App\Traits\Models\ForUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use ForUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'amount', 'in_out', 'description',
        'category_id', 'partner_id', 'creator_id',
    ];

    /**
     * Transaction belongs to user creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction belongs to category relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Transaction belongs to partner relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get transaction type attribute.
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->in_out ? __('transaction.income') : __('transaction.spending');
    }

    /**
     * Get transaction date_only attribute.
     *
     * @return string
     */
    public function getDateOnlyAttribute()
    {
        return substr($this->date, -2);
    }

    /**
     * Get transaction month attribute.
     *
     * @return string
     */
    public function getMonthAttribute()
    {
        return Carbon::parse($this->date)->format('m');
    }

    /**
     * Get transaction year attribute.
     *
     * @return string
     */
    public function getYearAttribute()
    {
        return Carbon::parse($this->date)->format('Y');
    }

    /**
     * Get transaction amount in string attribute.
     *
     * @return string
     */
    public function getAmountStringAttribute()
    {
        $amountString = number_format($this->amount, 2);

        if ($this->in_out == 0) {
            $amountString = '- '.$amountString;
        }

        return $amountString;
    }
}
