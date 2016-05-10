<?php

namespace Kblais\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    /**
     * Filter a result set.
     *
     * @param  Builder      $query
     * @param  QueryFilter $filters
     * @return Builder
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
