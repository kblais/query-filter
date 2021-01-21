<?php

namespace Kblais\QueryFilter;

use Kblais\QueryFilter\Commands\QueryFilterMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QueryFilterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('query-filter')
            ->hasConfigFile()
            ->hasCommand(QueryFilterMakeCommand::class)
        ;
    }
}
