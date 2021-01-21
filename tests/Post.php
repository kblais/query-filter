<?php

namespace Kblais\QueryFilter\Tests;

use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\Filterable;

class Post extends Model
{
    use Filterable;

    public $timestamps = false;

    protected $guarded = [];
}
