<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\BaseService;
use Lai3221\LaravelWise\Client;

class TransferService extends BaseService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->client = $client;
    }

    /**
     * Get transfer requirements
     *
     * @param array $data Transfer requirement data
     * @return array
     */
    public function requirement(array $data)
    {
        return $this->client->post('v1/transfer-requirements', $data);
    }

    /**
     * Create a transfer
     *
     * @param array $data Transfer data
     * @return array
     */
    public function createTransfer(array $data): array
    {
        return $this->client->post('v1/transfers', $data);
    }

    /**
     * Create a third party transfer
     *
     * @param int $profileId
     * @param array $data Transfer data
     * @return array
     */
    public function createThirdPartyTransfer(int $profileId, array $data): array
    {
        return $this->client->post("v2/profiles/{$profileId}/third-party-transfers", $data);
    }

    /**
     * Get a transfer by ID
     *
     * @param int $transferId
     * @return array
     */
    public function getTransfer(int $transferId): array
    {
        return $this->client->get("v1/transfers/{$transferId}");
    }

    /**
     * Get a third party transfer by ID
     *
     * @param int $profileId
     * @param int $transferId
     * @return array
     */
    public function getThirdPartyTransfer(int $profileId, int $transferId): array
    {
        return $this->client->get("v2/profiles/{$profileId}/third-party-transfers/{$transferId}");
    }

    /**
     * Fund a transfer
     *
     * @param int $profileId
     * @param int $transferId
     * @param array $data Funding data including type (e.g., "BALANCE")
     * @return array
     */
    public function fundTransfer(int $profileId, int $transferId, array $data): array
    {
        return $this->client->post("v3/profiles/{$profileId}/transfers/{$transferId}/payments", $data);
    }

    /**
     * List transfers for a profile
     *
     * @param array $params Query parameters (profile, status, sourceCurrency, targetCurrency, etc.)
     * @return array
     */
    public function listTransfers(array $params = []): array
    {
        return $this->client->get('v1/transfers', $params);
    }

    /**
     * Cancel a transfer
     *
     * @param int $transferId
     * @return array
     */
    public function cancelTransfer(int $transferId): array
    {
        return $this->client->put("v1/transfers/{$transferId}/cancel");
    }

    /**
     * Get a transfer receipt in PDF format
     *
     * @param int $transferId
     * @return string PDF content
     */
    public function getTransferPdf(int $transferId): string
    {
        return $this->client->get("v1/transfers/{$transferId}/receipt.pdf");
    }

    /**
     * Get a transfer's non objection certificate (NOC) for INR transfers
     *
     * @param int $transferId
     * @return string PDF content
     */
    public function getTransferNOC(int $transferId): string
    {
        return $this->client->get("v1/transfers/{$transferId}/documents/noc");
    }

    /**
     * List completed payments for a transfer
     *
     * @param int $transferId
     * @return array
     */
    public function getTransferPayments(int $transferId): array
    {
        return $this->client->get("v1/transfers/{$transferId}/payments");
    }

    /**
     * Get payout information for a transfer
     *
     * @param int $transferId
     * @return array
     */
    public function getPayoutInformation(int $transferId): array
    {
        return $this->client->get("v2/transfers/{$transferId}/invoices/bankingpartner");
    }
}
