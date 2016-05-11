<?php

namespace Kblais\QueryFilter\Tests;

class PostgreSQLQueryFilterTest extends QueryFilterTest
{
    public function testLikeFilterApplies()
    {
        $builder = $this->makeBuilder(Filters\PostLikeFilter::class);

        $expected = [
            "type" => "Basic",
            "column" => "title",
            "operator" => "ILIKE",
            "value" => "%foo%",
            "boolean" => "and",
        ];

        $this->assertContains($expected, $builder->getQuery()->wheres);
    }

    public function testTwoFiltersApplies()
    {
        $builder = $this->makeBuilder(Filters\PostTwoFilters::class);

        $expected = [
            [
                "type" => "Basic",
                "column" => "title",
                "operator" => "ILIKE",
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

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'pgsql');
    }
}
