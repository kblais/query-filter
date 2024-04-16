<?php

namespace Kblais\QueryFilter\Tests;

use Illuminate\Http\Request;
use Kblais\QueryFilter\QueryFilter;

final class QueryFilterTest extends TestCase
{
    public function testItDoesNotApplyWhenEmpty()
    {
        $queryBuilder = Post::filter(PostFilter::make());

        $eloquentBuilder = Post::query();

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );
    }

    public function testItFiltersByTitle()
    {
        $queryBuilder = Post::filter(PostFilter::make(['title' => 'Lorem ipsum']));

        $eloquentBuilder = Post::where('title', 'like', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testItFiltersByScope()
    {
        $queryBuilder = Post::filter(PostFilter::make(['content' => 'Lorem ipsum']));

        $eloquentBuilder = Post::where('content', 'LIKE', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testNonExistingFilterDoesNothing()
    {
        $queryBuilder = Post::filter(PostFilter::make(['anything' => 'Bar']));

        $eloquentBuilder = Post::query();

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterWithoutParameters()
    {
        $queryBuilder = Post::filter(PostFilter::make(['published' => true]));

        $eloquentBuilder = Post::where('published', true);

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterWithoutParametersWithFalse()
    {
        $queryBuilder = Post::filter(PostFilter::make(['published' => false]));

        $eloquentBuilder = Post::query();

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterWithoutParametersWithNull()
    {
        $queryBuilder = Post::filter(PostFilter::make(['published' => null]));

        $eloquentBuilder = Post::query();

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterByRequest()
    {
        $queryBuilder = Post::filter(
            new PostFilter(new Request(['title' => 'Lorem ipsum']))
        );

        $eloquentBuilder = Post::where('title', 'like', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterByRequestWithCustomGlobalConfig()
    {
        $this->app['config']->set('query-filter.default-filters-source', 'filters');

        $queryBuilder = Post::filter(
            new PostFilter(
                new Request(['filters' => ['title' => 'Lorem ipsum']])
            )
        );

        $eloquentBuilder = Post::where('title', 'like', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterByRequestWithCustomLocalConfig()
    {
        $request = new Request(['filters' => ['title' => 'Lorem ipsum']]);

        $filter = new class ($request) extends PostFilter {
            protected ?string $source = 'filters';
        };

        $queryBuilder = Post::filter($filter);

        $eloquentBuilder = Post::where('title', 'like', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testFilterByRequestWithLocalConfigOverriding()
    {
        $this->app['config']->set('query-filter.default-filters-source', 'filters');

        $request = new Request(['title' => 'Lorem ipsum']);

        $filter = new class ($request) extends PostFilter {
            protected ?string $source = null;
        };

        $queryBuilder = Post::filter($filter);

        $eloquentBuilder = Post::where('title', 'like', '%Lorem ipsum%');

        self::assertSame(
            $eloquentBuilder->toSql(),
            $queryBuilder->toSql()
        );

        self::assertSame(
            $eloquentBuilder->getBindings(),
            $queryBuilder->getBindings()
        );
    }

    public function testToArrayOutput()
    {
        $filterWithNull = PostFilter::make([
            'title' => 'Foo',
            'published' => null,
        ]);

        self::assertSame(
            ['title' => 'Foo'],
            $filterWithNull->toArray()
        );

        $filterWithUnknownKey = PostFilter::make([
            'title' => 'Foo',
            'author' => 'John',
        ]);

        self::assertSame(
            ['title' => 'Foo'],
            $filterWithUnknownKey->toArray()
        );
    }

    public function testDependencyInjection()
    {
        app()->instance(Request::class, new Request(['title' => 'Lorem ipsum']));

        $filter = app()->make(PostFilter::class);

        self::assertSame(
            ['title' => 'Lorem ipsum'],
            $filter->toArray()
        );
    }
}

/**
 * @mixin \Kblais\QueryFilter\Tests\Post
 */
class PostFilter extends QueryFilter
{
    public function title($value)
    {
        return $this->where('title', 'like', "%{$value}%");
    }

    public function published()
    {
        return $this->where('published', true);
    }

    public function content($value)
    {
        return $this->contentContains($value);
    }
}
