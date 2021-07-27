<?php

declare(strict_types = 1);

namespace Helldar\Cashier\Exceptions\Client;

class BuyerNotFoundClientException extends BaseClientException
{
    protected $status_code = 404;

    protected $reason = 'Buyer Not Found';
}
