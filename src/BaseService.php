<?php

namespace Lai3221\LaravelWise;

use Illuminate\Support\Facades\Cache;

class BaseService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all balances for the profile
     *
     * @param int $profileId
     * @return array
     */
    public function getBalances(int $profileId): array
    {
        $cacheKey = "wise_balances_{$profileId}";
        
        return Cache::remember($cacheKey, 300, function () use ($profileId) {
            return $this->client->get("borderless-accounts/{$profileId}/balances");
        });
    }

    /**
     * Get balance for a specific currency
     *
     * @param int $profileId
     * @param string $currency
     * @return array|null
     */
    public function getBalanceByCurrency(int $profileId, string $currency): ?array
    {
        $balances = $this->getBalances($profileId);
        
        foreach ($balances as $balance) {
            if ($balance['currency'] === $currency) {
                return $balance;
            }
        }

        return null;
    }

    /**
     * Create a transfer
     *
     * @param array $data
     * @return array
     */
    public function createTransfer(array $data): array
    {
        return $this->client->post('transfers', $data);
    }

    /**
     * Get transfer details
     *
     * @param int $transferId
     * @return array
     */
    public function getTransfer(int $transferId): array
    {
        return $this->client->get("transfers/{$transferId}");
    }

    /**
     * Cancel a transfer
     *
     * @param int $transferId
     * @return array
     */
    public function cancelTransfer(int $transferId): array
    {
        return $this->client->post("transfers/{$transferId}/cancel");
    }
} 