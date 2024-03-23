<?php

namespace Coverterror\YeastarForLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Coverterror\YeastarForLaravel\Commands\YeastarForLaravelCommand;

class YeastarForLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('yeastarforlaravel')
            ->hasConfigFile()
            ->hasMigration('create_yeastar_tokens_table');
    }
}
