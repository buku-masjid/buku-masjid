<?php

namespace App;

use App\Traits\Models\ConstantsGetter;
use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ForUser, ConstantsGetter;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $appends = ['status'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'color', 'status_id', 'creator_id'];

    /**
     * Category belongs to user creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category has many transactions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get category name label attribute.
     *
     * @return string
     */
    public function getNameLabelAttribute()
    {
        return '<span class="badge" style="background-color: '.$this->color.'">'.$this->name.'</span>';
    }

    public function getStatusAttribute()
    {
        return $this->status_id == static::STATUS_INACTIVE ? __('app.inactive') : __('app.active');
    }
}
