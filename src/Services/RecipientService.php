<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\BaseService;
use Lai3221\LaravelWise\Client;
use Ramsey\Uuid\Uuid;

class RecipientService extends BaseService
{

    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->client = $client;
    }

    /**
     * Create a recipient account
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->client->post('v1/accounts', $data);
    }

    /**
     * Create a refund recipient account
     *
     * @param array $data
     * @return array
     */
    public function createRefund(array $data): array
    {
        return $this->client->post('v1/refund-accounts', $data);
    }

    /**
     * List recipient accounts
     *
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array
    {
        return $this->client->get('v2/accounts', $params);
    }

    /**
     * Get account by ID
     *
     * @param int $accountId
     * @return array
     */
    public function get(int $accountId): array
    {
        return $this->client->get("v2/accounts/{$accountId}");
    }

    /**
     * Deactivate a recipient account
     *
     * @param int $accountId
     * @return array
     */
    public function deactivate(int $accountId): array
    {
        return $this->client->delete("v2/accounts/{$accountId}");
    }

    /**
     * Retrieve recipient account requirements dynamically
     *
     * @param int $quoteId
     * @return array
     */
    public function getRequirements(int $quoteId): array
    {
        return $this->client->get("v1/quotes/{$quoteId}/account-requirements");
    }

    public function getAccountRequirements(string $target): array
    {
        return $this->client->get("v1/account-requirements?source=SGD&target={$target}&sourceAmount=1000");
    }

    /**
     * Get recipient validation rules
     *
     * @param int $profileId
     * @param array $params
     * @return array
     */
    public function getValidationRules(int $profileId, array $params = []): array
    {
        return $this->client->get("profiles/{$profileId}/recipient-validation-rules", $params);
    }
}
