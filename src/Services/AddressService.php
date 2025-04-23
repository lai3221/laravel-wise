<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\BaseService;
use Lai3221\LaravelWise\Exceptions\WiseException;

class AddressService extends BaseService
{
    /**
     * Retrieve an address using its ID.
     *
     * @param int $addressId
     * @return array
     *
     * @throws WiseException
     */
    public function getAddress(int $addressId): array
    {
        return $this->client->get("v1/addresses/{$addressId}");
    }

    /**
     * List all addresses.
     *
     * @return array
     *
     * @throws WiseException
     */
    public function listAddresses(): array
    {
        return $this->client->get("v1/addresses");
    }

    /**
     * Create an address.
     *
     * @param array $data
     * @return array
     *
     * @throws WiseException
     */
    public function createAddress(array $data): array
    {
        return $this->client->post("v1/addresses", $data);
    }

    /**
     * Update an address.
     *
     * @param int $addressId
     * @param array $data
     * @return array
     *
     * @throws WiseException
     */
    public function updateAddress(int $addressId, array $data): array
    {
        return $this->client->patch("v1/addresses/{$addressId}", $data);
    }

    /**
     * Retrieve address requirements.
     *
     * Allows an optional array of query parameters.
     *
     * @param array $params
     * @return array
     *
     * @throws WiseException
     */
    public function getAddressRequirements(array $params = []): array
    {
        return $this->client->get("v1/addresses/requirements", $params);
    }

    /**
     * Submit address requirements.
     *
     * @param array $data
     * @return array
     *
     * @throws WiseException
     */
    public function postAddressRequirements(array $data): array
    {
        return $this->client->post("v1/addresses/requirements", $data);
    }
}
