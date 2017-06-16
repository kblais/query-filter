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
        
        if (empty($this->filters()) && method_exists($this, 'default')) {
            call_user_func([$this, 'default']);
        }

        foreach ($this->filters() as $name => $value) {
            $methodName = camel_case($name);
            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], array_filter([$value]));
            }
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
