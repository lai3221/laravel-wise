<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\Client;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class BalanceService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all balances for a profile
     *
     * @param int $profileId
     * @param string|null $type Filter balances by type (e.g., 'STANDARD')
     * @return array
     */
    public function listBalances(int $profileId, ?string $type = null): array
    {
        $params = [];
        if ($type) {
            $params['types'] = $type;
        }
        return $this->client->get("v4/profiles/{$profileId}/balances", $params);
    }

    /**
     * Get a specific balance by ID
     *
     * @param int $profileId
     * @param int $balanceId
     * @return array
     */
    public function getBalance(int $profileId, int $balanceId): array
    {
        return $this->client->get("v4/profiles/{$profileId}/balances/{$balanceId}");
    }

    /**
     * Create a balance account
     *
     * @param int $profileId
     * @param string $currency
     * @param string $type
     * @param string|null $name
     * @return array
     */
    public function createBalance(int $profileId, string $currency, string $type = 'STANDARD', ?string $name = null): array
    {
        $data = [
            'currency' => $currency,
            'type' => $type,
        ];

        if ($name !== null) {
            $data['name'] = $name;
        }

        return $this->client->post("v4/profiles/{$profileId}/balances", $data);
    }

    /**
     * Remove a balance account
     *
     * @param int $profileId
     * @param int $balanceId
     * @return void
     */
    public function removeBalance(int $profileId, int $balanceId): void
    {
        $this->client->delete("v4/profiles/{$profileId}/balances/{$balanceId}");
    }

    /**
     * Convert between balances
     *
     * @param int $profileId
     * @param string $quoteId
     * @return array
     */
    public function convertBalance(int $profileId, string $quoteId): array
    {
        return $this->client->post("v2/profiles/{$profileId}/balance-movements", [
            'quoteId' => $quoteId
        ]);
    }

    /**
     * Move money between balances
     *
     * @param int $profileId
     * @param int $sourceBalanceId
     * @param int $targetBalanceId
     * @param array $amount
     * @param string|null $quoteId
     * @return array
     */
    public function moveBalance(
        int $profileId, 
        int $sourceBalanceId, 
        int $targetBalanceId, 
        array $amount,
        ?string $quoteId = null
    ): array {
        $data = [
            'sourceBalanceId' => $sourceBalanceId,
            'targetBalanceId' => $targetBalanceId,
            'amount' => $amount
        ];

        if ($quoteId !== null) {
            $data['quoteId'] = $quoteId;
        }

        return $this->client->post("v2/profiles/{$profileId}/balance-movements", $data);
    }

    /**
     * Get deposit limits
     *
     * @param int $profileId
     * @param string $currency
     * @return array
     */
    public function getDepositLimits(int $profileId, string $currency): array
    {
        return $this->client->get("v1/profiles/{$profileId}/balance-capacity", [
            'currency' => $currency
        ]);
    }

    /**
     * Add an excess money account to a profile
     *
     * @param int $profileId
     * @param int $recipientId ID of the recipient for excess money transfers
     * @return array
     */
    public function addExcessMoneyAccount(int $profileId, int $recipientId): array
    {
        return $this->client->post("v1/profiles/{$profileId}/excess-money-account", [
            'recipientId' => $recipientId
        ]);
    }
} 