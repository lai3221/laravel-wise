<?php

namespace Lai3221\LaravelWise;

use Illuminate\Support\Facades\Cache;

class BaseService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
