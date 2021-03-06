<?php

/*
 * This file is part of the "cashier-provider/core" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@ai-rus.com>
 *
 * @copyright 2021 Andrey Helldar
 *
 * @license MIT
 *
 * @see https://github.com/cashier-provider/core
 */

declare(strict_types=1);

namespace CashierProvider\Core\Facades\Config;

use CashierProvider\Core\Config\Driver as Config;
use DragonCode\Contracts\Cashier\Config\Queues\Names;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getDriver()
 * @method static string getDetails()
 * @method static string|null getClientId()
 * @method static string|null getClientSecret()
 * @method static Names getQueue()
 */
class Driver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Config::class;
    }
}
