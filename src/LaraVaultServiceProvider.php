<?php

namespace Bi3\LaraVault;

use Bi3\LaraVault\Commands\LaraVaultCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaraVaultServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravault')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravault_table')
            ->hasCommand(LaraVaultCommand::class);
    }
}
