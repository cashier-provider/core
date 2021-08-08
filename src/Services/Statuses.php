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

namespace Helldar\Cashier\Services;

use Helldar\Cashier\Constants\Status;
use Helldar\Cashier\Facades\Config\Payment;
use Helldar\Contracts\Cashier\Helpers\Statuses as Contract;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Arr;
use Illuminate\Database\Eloquent\Model;

/** @method static Statuses make(Model $model) */
abstract class Statuses implements Contract
{
    use Makeable;

    public const NEW = [];

    public const REFUNDING = [];

    public const REFUNDED = [];

    public const FAILED = [];

    public const SUCCESS = [];

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function hasUnknown($status = null): bool
    {
        $bank = array_merge([
            static::NEW,
            static::REFUNDING,
            static::REFUNDED,
            static::FAILED,
            static::SUCCESS,
        ]);

        $model = [
            Status::NEW,
            Status::WAIT_REFUND,
            Status::REFUND,
            Status::FAILED,
            Status::SUCCESS,
        ];

        return ! $this->has($bank, $status)
            && ! $this->hasModel($model, $status);
    }

    public function hasCreated($status = null): bool
    {
        return $this->has(static::NEW, $status)
            || $this->hasModel([Status::NEW], $status);
    }

    public function hasFailed($status = null): bool
    {
        return $this->has(static::FAILED, $status)
            || $this->hasModel([Status::FAILED], $status);
    }

    public function hasRefunding($status = null): bool
    {
        return $this->has(static::REFUNDING, $status)
            || $this->hasModel([Status::WAIT_REFUND], $status);
    }

    public function hasRefunded($status = null): bool
    {
        return $this->has(static::REFUNDED, $status)
            || $this->hasModel([Status::REFUND], $status);
    }

    public function hasSuccess($status = null): bool
    {
        return $this->has(static::SUCCESS, $status)
            || $this->hasModel([Status::SUCCESS], $status);
    }

    public function inProgress($status = null): bool
    {
        return $this->hasCreated($status) || $this->hasRefunding($status);
    }

    protected function has(array $statuses, $status = null): bool
    {
        $status = ! is_null($status) ? $status : $this->cashierStatus();

        return ! is_null($status) && in_array($status, $statuses, true);
    }

    protected function hasModel(array $statuses, $status = null): bool
    {
        $statuses = Arr::map($statuses, static function (string $status) {
            return Payment::getStatuses()->getStatus($status);
        });

        $status = ! is_null($status) ? $status : $this->modelStatus();

        return $this->has($statuses, $status);
    }

    protected function cashierStatus(): ?string
    {
        if ($this->model->cashier && $this->model->cashier->details) {
            return $this->model->cashier->details->getStatus();
        }

        return null;
    }

    protected function modelStatus()
    {
        $field = Payment::getAttributes()->getStatus();

        return $this->model->getAttribute($field);
    }
}
