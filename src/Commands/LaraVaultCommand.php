<?php

namespace Bi3\LaraVault\Commands;

use Illuminate\Console\Command;

class LaraVaultCommand extends Command
{
    public $signature = 'laravault';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
