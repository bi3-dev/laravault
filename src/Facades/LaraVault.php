<?php

namespace Bi3\LaraVault\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bi3\LaraVault\LaraVault
 */
class LaraVault extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bi3\LaraVault\LaraVault::class;
    }
}
