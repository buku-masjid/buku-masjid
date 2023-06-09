<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait ForUser
{
    /**
     * Scope records by creator_id based on signedin user id.
     *
     * @return void
     */
    public static function bootForUser()
    {
        static::addGlobalScope('forUser', function (Builder $builder) {
            $builder->where('creator_id', auth()->id());
        });
    }
}
