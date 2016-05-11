<?php

use Kblais\QueryFilter\QueryFilter;

class PostTwoFilters extends QueryFilter
{
    public function title($value)
    {
        return $this->like('title', $value);
    }

    public function category($value)
    {
        return $this->equals('category', $value);
    }
}
