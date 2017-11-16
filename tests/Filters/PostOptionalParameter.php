<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostOptionalParameter extends QueryFilter
{
    public function category($value = 'foo')
    {
        $this->where('category', '=', $value);
    }
}
