<?php

namespace Kblais\QueryFilter\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\CoversNothing;

final class MakeCommandTest extends TestCase
{
    public function testFilterIsCreatedByMakeCommand()
    {
        Artisan::call('make:query-filter', ['name' => 'PostFilter']);

        self::assertTrue(File::exists(app_path('QueryFilters/PostFilter.php')));
    }

    public function testNestedFilterIsCreatedByMakeCommand()
    {
        Artisan::call('make:query-filter', ['name' => 'Foo/PostFilter']);

        self::assertTrue(File::exists(app_path('QueryFilters/Foo/PostFilter.php')));
    }
}
