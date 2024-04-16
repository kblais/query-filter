<?php

namespace Kblais\QueryFilter\Commands;

use Illuminate\Console\GeneratorCommand;

class QueryFilterMakeCommand extends GeneratorCommand
{
    protected $name = 'make:query-filter';

    protected $description = 'Create a new QueryFilter';

    protected $type = 'QueryFilter';

    public function getStub(): string
    {
        return __DIR__.'/stubs/QueryFilter.stub';
    }

    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\QueryFilters';
    }
}
