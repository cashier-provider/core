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

namespace Helldar\Cashier\Exceptions;

use Helldar\Cashier\Exceptions\Http\BadRequestClientException;
use Helldar\Contracts\Exceptions\Manager as Contract;
use Helldar\Contracts\Http\Builder;
use Helldar\Support\Facades\Helpers\Arr;

abstract class Manager implements Contract
{
    protected $codes = [];

    protected $default = BadRequestClientException::class;

    protected $code_keys = ['StatusCode', 'Code'];

    protected $reason_keys = ['Message', 'Data'];

    public function throw(Builder $uri, int $code, array $response): void
    {
        $code = $this->getCode($code, $response);

        $exception = $this->getException($code);

        $reason = $this->getReason($response);

        throw new $exception($uri, $reason);
    }

    protected function getCode(int $code, array $response): int
    {
        return $this->getCodeByResponseContent($response) ?: $code;
    }

    protected function getCodeByResponseContent(array $response): ?int
    {
        foreach ($this->code_keys as $key) {
            if ($code = Arr::get($response, $key)) {
                return (int) $code;
            }
        }

        return null;
    }

    protected function getException(int $code): string
    {
        return Arr::get($this->codes, $code, $this->default);
    }

    protected function getReason(array $response): ?string
    {
        foreach ($this->reason_keys as $key) {
            if ($value = Arr::get($response, $key)) {
                if (is_string($value) && ! empty($value)) {
                    return $value;
                }
            }
        }

        return null;
    }
}