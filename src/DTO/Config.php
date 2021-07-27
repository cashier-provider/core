<?php

declare(strict_types=1);

namespace Helldar\Cashier\DTO;

use Helldar\Contracts\Cashier\DTO\Config as Contract;
use Helldar\Support\Concerns\Makeable;

class Config implements Contract
{
    use Makeable;

    protected $driver;

    protected $request;

    protected $client_id;

    protected $client_secret;

    public function __construct(array $data)
    {
        /*
         * @var string $driver
         * @var string $request
         * @var string $client_id
         * @var string $client_secret
         */
        extract($data);

        $this->driver        = $driver ?? null;
        $this->request       = $request ?? null;
        $this->client_id     = $client_id ?? null;
        $this->client_secret = $client_secret ?? null;
    }

    /**
     * @return \Helldar\Contracts\Cashier\Driver|string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function getClientSecret(): ?string
    {
        return $this->client_secret;
    }
}
