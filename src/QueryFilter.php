<?php

namespace Kblais\QueryFilter;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionMethod;
use ReflectionParameter;

abstract class QueryFilter implements Arrayable
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
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            return \call_user_func_array([$this->builder, $name], $arguments);
        }
    }

    /**
     * Apply the filters to the builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if (empty($this->filters()) && method_exists($this, 'default')) {
            \call_user_func([$this, 'default']);
        }

        foreach ($this->filters() as $name => $value) {
            $methodName = Str::camel($name);
            $value = array_filter([$value]);
            if ($this->shouldCall($methodName, $value)) {
                \call_user_func_array([$this, $methodName], $value);
            }
        }

        return $this->builder;
    }

    public function toArray()
    {
        return array_filter($this->filters(), function ($filter) {
            return !empty($filter);
        });
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
     * Helper for "=" filter.
     *
     * @param string $column
     * @param string $value
     *
     * @return Builder
     */
    protected function equals($column, $value)
    {
        return $this->builder->where($column, $value);
    }

    /**
     * Helper for "LIKE" filter.
     *
     * @param string $column
     * @param string $value
     *
     * @return Builder
     */
    protected function like($column, $value)
    {
        if ('pgsql' === $this->builder->getQuery()->getConnection()->getDriverName()) {
            return $this->builder->where($column, 'ILIKE', '%'.$value.'%');
        }

        return $this->builder->where($column, 'LIKE', '%'.$value.'%');
    }

    /**
     * Make sure the method should be called.
     *
     * @param string $methodName
     * @param array  $value
     *
     * @return bool
     */
    protected function shouldCall($methodName, array $value)
    {
        if (!method_exists($this, $methodName)) {
            return false;
        }

        $method = new ReflectionMethod($this, $methodName);
        /** @var ReflectionParameter $parameter */
        $parameter = Arr::first($method->getParameters());

        return $value ? $method->getNumberOfParameters() > 0 :
            null === $parameter || $parameter->isDefaultValueAvailable();
    }
}
