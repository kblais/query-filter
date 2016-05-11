<?php

use Kblais\QueryFilter\QueryFilter;

class PostLikeFilter extends QueryFilter
{
    public function title($value)
    {
        return $this->like('title', $value);
    }
}
