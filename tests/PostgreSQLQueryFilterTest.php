<?php

namespace Kblais\QueryFilter\Tests;

/**
 * @internal
 * @covers \Kblais\QueryFilter\QueryFilter
 */
final class PostgreSQLQueryFilterTest
{
    public function testLikeFilterApplies()
    {
        $builder = $this->makeBuilder(Filters\PostLikeFilter::class);

        $expected = [
            'type' => 'Basic',
            'column' => 'title',
            'operator' => 'ILIKE',
            'value' => '%foo%',
            'boolean' => 'and',
        ];

        static::assertContains($expected, $builder->getQuery()->wheres);
    }

    public function testTwoFiltersApplies()
    {
        $builder = $this->makeBuilder(Filters\PostTwoFilters::class);

        $expected = [
            [
                'type' => 'Basic',
                'column' => 'title',
                'operator' => 'ILIKE',
                'value' => '%foo%',
                'boolean' => 'and',
            ],
            [
                'type' => 'Basic',
                'column' => 'category',
                'operator' => '=',
                'value' => 'bar',
                'boolean' => 'and',
            ],
        ];

        static::assertArraySubset($expected, $builder->getQuery()->wheres);
    }

    private function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'pgsql');
    }

    /**
     * @param $className
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function makeBuilder($className, Request $request = null)
    {
        $request = $request ?: $this->makeRequest();

        $filters = new $className($request);

        return Models\Post::filter($filters);
    }
}
