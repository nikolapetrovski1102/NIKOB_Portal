<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PhpRedis extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'PhpRedis';
    }
}
