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

namespace CashierProvider\Core\Config;

use DragonCode\Contracts\Cashier\Config\Logs as Contract;

class Logs implements Contract
{
    public function isEnabled(): bool
    {
        return config('cashier.logs.enabled', true);
    }

    public function getConnection(): ?string
    {
        return config('cashier.logs.connection');
    }

    public function getTable(): string
    {
        return config('cashier.logs.table', 'cashier_logs');
    }
}
