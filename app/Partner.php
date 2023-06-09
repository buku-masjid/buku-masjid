<?php

namespace App;

use App\Traits\Models\ConstantsGetter;
use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use ForUser, ConstantsGetter;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'status_id', 'creator_id'];

    /**
     * Partner belongs to user creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Partner has many transactions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get partner name label attribute.
     *
     * @return string
     */
    public function getNameLabelAttribute()
    {
        return '<span class="badge badge-pill badge-secondary">'.$this->name.'</span>';
    }

    public function getStatusAttribute()
    {
        return $this->status_id == static::STATUS_INACTIVE ? __('app.inactive') : __('app.active');
    }
}
