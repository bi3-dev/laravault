<?php

namespace Voidoflimbo\LaraVault\Commands;

use RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\{
    confirm,
    info,
    note,
    error
};

class LaraVaultCommand extends Command
{
    protected $signature = 'laravault:install';

    protected $description = 'Install Breeze, Livewire and copy reusable resources.';

    public function handle()
    {
        // Inform the user that Laravel Breeze is being installed
        info('Installing Laravel Breeze...');
        if (! $this->runProcess(['composer', 'require', 'laravel/breeze', '--dev'])) {
            return 1;
        }
        info('Laravel Breeze Installed !');

        // Inform the user that Breeze scaffolding is being published
        info('Publishing Breeze scaffolding...');
        if (! $this->runProcess(['php', 'artisan', 'breeze:install', 'blade', '--pest'])) {
            return 1;
        }
        info('Published Breeze scaffolding !');

        // TODO: Complete replacing of all default blade files
        // Replace Breeze Blade files with custom files
        info('Replacing Breeze Blade files with custom files...');
        $this->replaceFiles(
            resource_path('views/auth'),
            $this->packageResourcePath('views/auth')
        );
        info('Breeze Blade files replaced with custom files !');

        // Inform the user that Livewire is being installed
        info('Installing Livewire...');
        if (! $this->runProcess(['composer', 'require', 'livewire/livewire'])) {
            return 1;
        }
        info('Livewire Installed !');

        // Replace the app.js file with a custom file
        info('Replacing app.js file...');
        $this->replaceFiles(
            resource_path('js/app.js'),
            $this->packageResourcePath('js/app.js')
        );
        info('app.js Replaced !');

        // Replace the public directory
        info('Replacing app.js file...');
        $this->replaceFiles(
            public_path('img/logo.ico'),
            $this->packagePublicPath('img/logo.ico')
        );
        $this->replaceFiles(
            public_path('img/logo.png'),
            $this->packagePublicPath('img/logo.png')
        );
        $this->replaceFiles(
            public_path('img/login_bg.png'),
            $this->packagePublicPath('img/login_bg.png')
        );
        info('app.js Replaced !');

        // Copy over the Blade anonymous components
        info('Copying anonymous blade components');
        $this->replaceFiles(
            resource_path('views/components'),
            $this->packageResourcePath('views/components')
        );
        info('Anonymous blade components copied');

        // Output a blank line for readability
        $this->line('');

        // Inform the user that the installation is complete
        info('LaraVault installation complete.');
        $this->line('');

        // Ask the user if they want to run npm install and npm run build
        if (confirm(
            label: 'Do you want to run npm install && npm run build',
            default: true,
            yes: 'Yes run now',
            no: 'No I will install it later',
            hint: 'Install later only if specific use case demands it'
        )) {
            $this->runCommands(['npm install', 'npm run build']);
            $this->line('');
        }

        // Display a note about replacing images in the public/img directory
        note('You should probably replace followings in public/img \n - logo.png\n - logo.ico\n - login_bg.png');
        return 0;
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands(array $commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }

    /**
     * Run a process with the given command.
     *
     * @param  array  $command
     * @param  int  $time
     * @return bool
     */
    protected function runProcess(array $command, int $time = 300)
    {
        $process = new Process($command);
        $process->setTimeout($time);
        $process->setTty(Process::isTtySupported());
        $process->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                error($buffer);
            } else {
                info($buffer);
            }
        });

        if (! $process->isSuccessful()) {
            error('Failed to execute: ' . implode(' ', $command));

            return false;
        }

        return true;
    }

    /**
     * Replace files or directories in the target path with those from the source path.
     *
     * @param  string  $targetPath
     * @param  string  $sourcePath
     * @return void
     */
    protected function replaceFiles($targetPath, $sourcePath)
    {
        $filesystem = new Filesystem();

        if ($filesystem->exists($targetPath)) {
            if ($filesystem->isDirectory($targetPath)) {
                $filesystem->deleteDirectory($targetPath);
            } else {
                $filesystem->delete($targetPath);
            }
        }

        if ($filesystem->isDirectory($sourcePath)) {
            $filesystem->copyDirectory($sourcePath, $targetPath);
        } else {
            $filesystem->copy($sourcePath, $targetPath);
        }
    }

    /**
     * Get the package resource path.
     *
     * @param  string  $path
     * @return string
     */
    protected function packageResourcePath($path)
    {
        return __DIR__ . '/../../resources/' . $path;
    }

    /**
     * Get the package public path.
     *
     * @param  string  $path
     * @return string
     */
    protected function packagePublicPath($path)
    {
        return __DIR__ . '/../../public/' . $path;
    }
}
