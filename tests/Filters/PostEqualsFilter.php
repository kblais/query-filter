<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostEqualsFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->equals('category', $value);
    }
}
