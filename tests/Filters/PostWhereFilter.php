<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostWhereFilter extends QueryFilter
{
    public function title($value)
    {
        $this->where('title', 'like', "%{$value}%");
    }

    public function age($value)
    {
        $this->where('age', '>=', $value);
    }
}
