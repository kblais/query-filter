<?php

namespace Kblais\QueryFilter\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\FilterableTrait;

class Post extends Model
{
    use FilterableTrait;

    protected $fillable = ['title', 'content', 'category'];
}
