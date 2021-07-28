<?php

declare(strict_types=1);

namespace Helldar\Cashier\Jobs;

use Helldar\Cashier\Facades\Config\Main;
use Helldar\Cashier\Facades\Config\Payment;
use Helldar\Cashier\Facades\Helpers\DriverManager;
use Helldar\Contracts\Cashier\Driver;
use Helldar\Contracts\Cashier\Resources\Response;
use Helldar\Support\Concerns\Makeable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

abstract class Base implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Makeable;

    /** @var \Helldar\Cashier\Concerns\Casheable|\Illuminate\Database\Eloquent\Model */
    public $model;

    public $force_break;

    /** @var \Helldar\Contracts\Cashier\Driver */
    protected $driver;

    public function __construct(Model $model, bool $force_break = false)
    {
        $this->model = $model;

        $this->force_break = $force_break;
    }

    abstract public function handle();

    abstract protected function process(): Response;

    public function retryUntil(): Carbon
    {
        $timeout = Main::getCheckTimeout();

        return Carbon::now()->addSeconds($timeout);
    }

    protected function driver(): Driver
    {
        if (! empty($this->driver)) {
            return $this->driver;
        }

        return $this->driver = DriverManager::fromModel($this->model);
    }

    protected function hasBreak(): bool
    {
        return $this->force_break;
    }

    protected function store(Response $details): void
    {
        $payment_id = $details->getPaymentId();

        $this->model->cashier()->updateOrCreate(compact('payment_id'), compact('details'));
    }

    protected function modelId(): string
    {
        return $this->model->getKey();
    }

    protected function returnToQueue(): void
    {
        $delay = Main::getCheckDelay();

        $this->release($delay);
    }

    protected function updateParentStatus(string $status): void
    {
        $attribute = $this->attributeStatus();
        $status    = $this->status($status);

        $this->model->update([
            $attribute => $status,
        ]);
    }

    protected function attributeStatus(): string
    {
        return Payment::getAttributes()->getStatus();
    }

    protected function status(string $status)
    {
        return Payment::getStatuses()->getStatus($status);
    }

    protected function hasSuccess(string $status): bool
    {
        return $this->driver()->statuses()->hasSuccess($status);
    }

    protected function hasFailed(string $status): bool
    {
        return $this->driver()->statuses()->hasFailed($status);
    }

    protected function hasRefunding(string $status): bool
    {
        return $this->driver()->statuses()->hasRefunding($status);
    }

    protected function hasRefunded(string $status): bool
    {
        return $this->driver()->statuses()->hasRefunded($status);
    }
}
