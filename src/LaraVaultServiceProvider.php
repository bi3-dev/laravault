<?php

namespace Voidoflimbo\LaraVault;

use Illuminate\Support\Facades\Blade;
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
            ->hasCommand(LaraVaultCommand::class)
            ->hasViews();
    }

    public function boot()
    {
        parent::boot();

        // Register the views
        // $this->loadViewsFrom(__DIR__ . '/../../resources/views/voidoflimbo', 'voidoflimbo');

        // Register anonymous Blade components
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/voidoflimbo', 'voidoflimbo');
    }
}
