<?php

namespace Voidoflimbo\LaraVault\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class LaraVaultCommand extends Command
{
    protected $signature = 'laravault:install';

    protected $description = 'Install Breeze, Livewire and copy reusable resources.';

    public function handle()
    {
        $this->info('Installing Laravel Breeze...');
        if (! $this->runProcess(['composer', 'require', 'laravel/breeze', '--dev'])) {
            return 1;
        }

        $this->info('Publishing Breeze scaffolding...');
        if (! $this->runProcess(['php', 'artisan', 'breeze:install', 'blade', '--pest'])) {
            return 1;
        }

        $this->info('Replacing Breeze Blade files with custom files...');
        $this->replaceFiles(
            resource_path('views/auth'),
            $this->packageResourcePath('views/auth')
        );

        $this->info('Installing Livewire...');
        if (! $this->runProcess(['composer', 'require', 'livewire/livewire'])) {
            return 1;
        }

        $this->info('Replacing app.js file...');
        $this->replaceFile(
            resource_path('js/app.js'),
            $this->packageResourcePath('js/app.js')
        );

        $this->info('LaraVault installation complete.');
        $this->info('Please run the following commands to complete the setup:');
        $this->info('- npm install');
        $this->info('- npm run dev');
        $this->info('Also please copy these images in public/img');
        $this->info('- logo.png');
        $this->info('- logo.ico');
        $this->info('- login_bg.png');

        return 0;
    }

    protected function runProcess(array $command)
    {
        $process = new Process($command);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Failed to execute: '.implode(' ', $command));
            $this->error($process->getErrorOutput());
            $this->info($process->getOutput());

            return false;
        }

        return true;
    }

    protected function replaceFiles($targetPath, $sourcePath)
    {
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }

        File::copyDirectory($sourcePath, $targetPath);
    }

    protected function replaceFile($targetFile, $sourceFile)
    {
        if (File::exists($targetFile)) {
            File::delete($targetFile);
        }

        File::copy($sourceFile, $targetFile);
    }

    protected function packageResourcePath($path)
    {
        return __DIR__.'/../../resources/'.$path;
    }
}
