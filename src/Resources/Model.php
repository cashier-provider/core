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

namespace Helldar\Cashier\Resources;

use Helldar\Cashier\Facades\Helpers\Date;
use Helldar\Cashier\Facades\Helpers\Unique;
use Helldar\Contracts\Cashier\Resources\Model as Contract;
use Helldar\Support\Concerns\Makeable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Carbon;

abstract class Model implements Contract
{
    use Makeable;

    protected $model;

    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    public function getClientId(): string
    {
        return $this->clientId();
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret();
    }

    public function getUniqueId(bool $every = false): string
    {
        return Unique::id($every);
    }

    public function getPaymentId(): string
    {
        return $this->paymentId();
    }

    public function getSum(): int
    {
        $sum = $this->sum();

        return (int) ($sum * 100);
    }

    public function getCurrency(): string
    {
        return $this->currency();
    }

    public function getCreatedAt(): string
    {
        $date = $this->createdAt();

        return Date::toString($date);
    }

    public function getExternalId(): ?string
    {
        return $this->model->cashier->external_id ?? null;
    }

    abstract protected function clientId(): string;

    abstract protected function clientSecret(): string;

    abstract protected function paymentId(): string;

    abstract protected function sum(): float;

    abstract protected function currency(): string;

    abstract protected function createdAt(): Carbon;
}