<?php

use Kblais\QueryFilter\QueryFilter;

class PostRawFilter extends QueryFilter
{
    public function is_long()
    {
        return $this->builder->whereRaw('LENGTH(category) > ?', [10]);
    }
}
