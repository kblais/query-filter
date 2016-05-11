<?php

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class QueryFilterTest extends TestCase
{
    public function testLikeFilterApplies()
    {
        $builder = $this->makeBuilder(PostLikeFilter::class);

        $expected = [
            "type" => "Basic",
            "column" => "title",
            "operator" => "LIKE",
            "value" => "%foo%",
            "boolean" => "and",
        ];

        $this->assertContains($expected, $builder->getQuery()->wheres);
    }

    public function testEqualsFilterApplies()
    {
        $builder = $this->makeBuilder(PostEqualsFilter::class);

        $expected = [
            "type" => "Basic",
            "column" => "category",
            "operator" => "=",
            "value" => "bar",
            "boolean" => "and",
        ];

        $this->assertContains($expected, $builder->getQuery()->wheres);
    }

    public function testRawFilterApplies()
    {
        $builder = $this->makeBuilder(PostRawFilter::class);

        $expected = [
            "type" => "raw",
            "sql" => "LENGTH(category) > ?",
            "boolean" => "and",
        ];

        $this->assertContains($expected, $builder->getQuery()->wheres);
    }

    public function testTwoFiltersApplies()
    {
        $builder = $this->makeBuilder(PostTwoFilters::class);

        $expected = [
            [
                "type" => "Basic",
                "column" => "title",
                "operator" => "LIKE",
                "value" => "%foo%",
                "boolean" => "and",
            ],
            [
                "type" => "Basic",
                "column" => "category",
                "operator" => "=",
                "value" => "bar",
                "boolean" => "and",
            ],
        ];

        $this->assertArraySubset($expected, $builder->getQuery()->wheres);
    }

    public function testNoFilterApplies()
    {
        $builder = $this->makeBuilder(PostNoFilter::class);

        $this->assertempty($builder->getQuery()->wheres);
    }

    protected function makeRequest()
    {
        $request = new Request;

        $request->merge([
            'title' => 'foo',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, adipisci!',
            'category' => 'bar',
            'is_long' => null,
        ]);

        return $request;
    }

    protected function makeBuilder($classname)
    {
        $request = $this->makeRequest();

        $filters = new $classname($request);

        return Post::filter($filters);
    }
}
