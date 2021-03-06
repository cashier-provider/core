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

namespace CashierProvider\Core\Console\Commands;

use CashierProvider\Core\Services\Jobs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Refund extends Base
{
    protected $signature = 'cashier:refund';

    protected $description = 'Launching the command to check payments for refunds';

    public function handle()
    {
        $this->payments()->chunk($this->count, function (Collection $payments) {
            $payments->each(function (Model $payment) {
                if ($this->allowCancelByStatus($payment)) {
                    $this->cancel($payment);
                }
            });
        });
    }

    protected function cancel(Model $payment): void
    {
        Jobs::make($payment)->refund();
    }

    protected function allowCancelByStatus(Model $payment): bool
    {
        $statuses = $this->driver($payment)->statuses();

        return $statuses->inProgress();
    }
}
