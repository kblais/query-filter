<?php

namespace Kblais\QueryFilter\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\Filterable;

class Post extends Model
{
    use Filterable;

    public $timestamps = false;

    protected $guarded = [];

    public function scopeContentContains(Builder $query, $value)
    {
        $query->where('content', 'LIKE', "%{$value}%");
    }
}
