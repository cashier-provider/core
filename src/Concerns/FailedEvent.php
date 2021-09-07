<?php

/*
 * This file is part of the "andrey-helldar/cashier" project.
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
 * @see https://github.com/andrey-helldar/cashier
 */

declare(strict_types=1);

namespace Helldar\Cashier\Concerns;

use Helldar\Cashier\Events\Http\ExceptionEvent;
use Throwable;

trait FailedEvent
{
    protected function failedEvent(Throwable $e): void
    {
        event(
            new ExceptionEvent($e)
        );
    }
}
