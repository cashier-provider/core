<?php

namespace Helldar\Cashier\Exceptions;

use RuntimeException;

final class UnknownMethodException extends RuntimeException
{
    public function __construct(string $class, string $method)
    {
        $message = 'The ' . $class . ' class does not contain a ' . $method . ' method.';

        parent::__construct($message);
    }
}