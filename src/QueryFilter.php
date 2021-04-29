<?php

namespace Kblais\QueryFilter;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionClass;
use ReflectionMethod;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
abstract class QueryFilter implements Arrayable
{
    use ForwardsCalls;

    protected Builder $builder;

    protected array $filters = [];

    protected ?string $source = null;

    final public function __construct(Request $request = null)
    {
        if ($request) {
            $this->setFiltersFromRequest($request);
        }
    }

    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->builder, $method, $parameters);
    }

    public static function make(array $filters = []): self
    {
        return (new static())->setFilters($filters);
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $name => $value) {
            $methodName = Str::camel($name);

            if ($this->shouldCallMethod($methodName, $value)) {
                $this->{$methodName}($value);
            }
        }
    }

    public function setFilters(array $filters = []): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function setFiltersFromRequest(Request $request): self
    {
        return $this->setFilters(
            $request->input(
                $this->source ?: config('query-filter.default-filters-source'),
                []
            )
        );
    }

    public function toArray()
    {
        $getName = fn ($method) => $method->getName();

        $classOnlyMethods = array_diff(
            array_map($getName, (new ReflectionClass($this))->getMethods()),
            array_map($getName, (new ReflectionClass(self::class))->getMethods())
        );

        return array_intersect_key(
            array_filter(
                $this->filters,
                function ($filter) {
                    return !empty($filter);
                }
            ),
            array_flip($classOnlyMethods)
        );
    }

    protected function shouldCallMethod(string $methodName, $value): bool
    {
        if (method_exists($this, $methodName)) {
            $method = new ReflectionMethod($this, $methodName);

            return (1 === $method->getNumberOfRequiredParameters() && null !== $value)
                || (0 === $method->getNumberOfRequiredParameters() && (bool) $value)
            ;
        }

        return false;
    }
}
