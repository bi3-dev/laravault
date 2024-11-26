<?php

namespace Voidoflimbo\LaraVault;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Voidoflimbo\LaraVault\Commands\LaraVaultCommand;

class LaraVaultServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravault')
            ->hasConfigFile()
            ->hasCommand(LaraVaultCommand::class);
    }
}
