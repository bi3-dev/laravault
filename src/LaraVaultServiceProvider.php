<?php

namespace Voidoflimbo\LaraVault;

use Voidoflimbo\LaraVault\Commands\LaraVaultCommand;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class LaraVaultServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravault')
            ->hasConfigFile()
            ->hasViews()
            ->hasInstallCommand(
                fn(InstallCommand $command) =>
                $command
                    ->startWith(fn(InstallCommand $command) => $this->runInstallCommand($command))
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->endWith(fn(InstallCommand $command) => $this->informUser($command))
            );
    }

    public function boot()
    {
        parent::boot();

        // Register the views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/voidoflimbo', 'voidoflimbo');

        // Register anonymous Blade components
        // Blade::anonymousComponentNamespace('laravault::components', 'voidoflimbo');
        Blade::anonymousComponentPath(__DIR__ . '/../../resources/views/voidoflimbo', 'voidoflimbo');
    }

    protected function runInstallCommand(InstallCommand $command)
    {
        $exitCode = Artisan::call('laravault:install');

        if ($exitCode !== 0) {
            $command->error('LaraVault installation failed.');
            exit(1); // Exit the installation process
        }
    }

    protected function informUser(InstallCommand $command)
    {
        $command->info('Please run the following commands to complete the setup:');
        $command->info('npm install');
        $command->info('npm run dev');
    }
}
