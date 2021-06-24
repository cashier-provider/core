<?php

namespace Helldar\Cashier\Facade\Config;

use Helldar\Cashier\Helpers\Config\Driver as Config;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Helldar\Cashier\Contracts\Driver get(string $type_id)
 */
final class Driver extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Config::class;
    }
}