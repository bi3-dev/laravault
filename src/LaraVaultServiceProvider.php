<?php

namespace Voidoflimbo\LaraVault;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaraVaultServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravault')
            ->hasConfigFile()
            ->hasViews();
    }

    public function boot()
    {
        parent::boot();

        // Register the views
        // $this->loadViewsFrom(__DIR__ . '/../../resources/views/voidoflimbo', 'voidoflimbo');

        // Register anonymous Blade components
        Blade::anonymousComponentPath(__DIR__ . '/../../resources/views/voidoflimbo', 'voidoflimbo');
    }
}
