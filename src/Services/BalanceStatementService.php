<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\BaseService;
use Lai3221\LaravelWise\Client;
use Illuminate\Support\Facades\Cache;
use Lai3221\LaravelWise\Exceptions\ApiException;
use Lai3221\LaravelWise\Exceptions\AuthenticationException;
use Lai3221\LaravelWise\Exceptions\NotFoundException;
use Lai3221\LaravelWise\Exceptions\ValidationException;
use Ramsey\Uuid\Uuid;

class BalanceStatementService extends BaseService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->client = $client;
    }

    /**
     * List of activities belonging to user profile.
     * @see https://docs.wise.com/api-docs/api-reference/balance-statement
     * @param int $profileId
     * @param string|null $balanceId
     * @param array|null $data
     * @return array
     * @throws ApiException
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function retrievingBalanceStatement(int $profileId, ?string $balanceId, ?array $data): array
    {
        return $this->client->get("v1/profiles/{$profileId}/balance-statements/{$balanceId}/statement.json", $data);
    }
}
