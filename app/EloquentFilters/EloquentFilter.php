<?php

namespace App\EloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class EloquentFilter
{
    protected $queryBuilder;

    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    abstract public function apply(Request $request);

    protected function filterBySearchQuery($searchQuery, array $searchKeys)
    {
        if (is_null($searchQuery) || empty($searchKeys)) {
            return;
        }

        $this->queryBuilder->where(function ($query) use ($searchQuery, $searchKeys) {
            foreach ($searchKeys as $key) {
                $query->orWhere($key, 'like', '%'.$searchQuery.'%');
            }
        });
    }
}
