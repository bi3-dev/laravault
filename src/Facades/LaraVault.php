<?php

namespace Voidoflimbo\LaraVault\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Voidoflimbo\LaraVault\LaraVault
 */
class LaraVault extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Voidoflimbo\LaraVault\LaraVault::class;
    }
}
