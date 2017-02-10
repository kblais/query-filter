<?php

namespace Kblais\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    /**
     * The request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if (empty($this->filters()) && method_exists($this, '_init')) {
            call_user_func([$this, '_init']);
        }
        
        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, camel_case($name))) {
                continue;
            }

            call_user_func_array([$this, camel_case($name)], array_filter([$value]));
        }

        return $this->builder;
    }

    /**
     * Get all request filters data.
     *
     * @return array
     */
    protected function filters()
    {
        return $this->request->all();
    }

    /**
     * Helper for "=" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function equals($column, $value)
    {
        return $this->builder->where($column, $value);
    }

    /**
     * Helper for "LIKE" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function like($column, $value)
    {
        if ($this->builder->getQuery()->getConnection()->getDriverName() == 'pgsql') {
            return $this->builder->where($column, 'ILIKE', '%' . $value . '%');
        }

        return $this->builder->where($column, 'LIKE', '%' . $value . '%');
    }
}
