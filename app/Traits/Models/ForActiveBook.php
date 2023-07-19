<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait ForActiveBook
{
    public static function bootForActiveBook()
    {
        static::addGlobalScope('forActiveBook', function (Builder $builder) {
            $builder->where('book_id', auth()->activeBookId());
        });
    }
}
