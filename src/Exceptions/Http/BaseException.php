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

namespace CashierProvider\Core\Exceptions\Http;

use CashierProvider\Core\Concerns\Exceptionable;
use DragonCode\Contracts\Exceptions\Http\ClientException;
use DragonCode\Contracts\Http\Builder;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class BaseException extends HttpException implements ClientException
{
    use Exceptionable;

    public $default_status_code = 400;

    public function __construct(Builder $uri, ?string $reason = null)
    {
        $message = $this->message($uri, $reason);

        $code = $this->getStatus();

        parent::__construct($code, $message, null, [], $code);
    }

    protected function message(Builder $uri, ?string $reason): string
    {
        $reason = $reason ?: $this->getReason();

        return $uri->toUrl() . ': ' . $reason;
    }
}
