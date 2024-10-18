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
            ->hasViews()
            ->hasInstallCommand(
                function (InstallCommand $command) {
                    $command
                        ->startWith(function (InstallCommand $command) {
                            return $this->runInstallCommand($command);
                        })
                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->endWith(function (InstallCommand $command) {
                            return $this->informUser($command);
                        });
                }
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
