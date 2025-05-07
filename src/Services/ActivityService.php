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

class ActivityService extends BaseService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->client = $client;
    }

    /**
     * List of activities belonging to user profile.
     * @see https://docs.wise.com/api-docs/api-reference/activity#list
     * @param int $profileId
     * @param array|null $data
     * @return array
     * @throws ApiException
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function listActivities(int $profileId, ?array $data = null): array
    {
        return $this->client->get("v1/profiles/{$profileId}/activities", $data);
    }
}
