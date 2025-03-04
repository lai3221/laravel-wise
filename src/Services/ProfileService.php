<?php

namespace Lai3221\LaravelWise\Services;

use Lai3221\LaravelWise\Client;
use Ramsey\Uuid\Uuid;

class ProfileService extends \App\Services\Wise\ProfileService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a personal profile
     *
     * @param array $data Profile data including:
     *                    - firstName (required, max 30 chars)
     *                    - lastName (required, max 30 chars)
     *                    - preferredName (optional, max 30 chars)
     *                    - dateOfBirth (required, yyyy-mm-dd)
     *                    - nationality (optional, 3 letter country code)
     *                    - address (required array with address details)
     *                    - contactDetails (required array with email and phone)
     *                    - occupations (optional array for CA, IN, JP, US-AZ, US-NM)
     *                    - externalCustomerId (optional)
     * @return array
     */
    public function createPersonalProfile(array $data): array
    {
        $idempotenceUuid = Uuid::uuid4()->toString();
        return $this->client->post('v2/profiles/personal-profile', $data, [
            'X-idempotence-uuid' => $idempotenceUuid
        ]);
    }

    /**
     * Create a business profile
     *
     * @param array $data Business profile data
     * @return array
     */
    public function createBusinessProfile(array $data): array
    {
        $idempotenceUuid = Uuid::uuid4()->toString();
        return $this->client->post('v2/profiles/business-profile', $data, [
            'X-idempotence-uuid' => $idempotenceUuid
        ]);
    }

    /**
     * Update a personal profile
     *
     * @param int $profileId
     * @param array $data Update data
     * @return array
     */
    public function updatePersonalProfile(int $profileId, array $data): array
    {
        return $this->client->put("v2/profiles/{$profileId}/personal-profile", $data);
    }

    /**
     * Update a business profile
     *
     * @param int $profileId
     * @param array $data Update data
     * @return array
     */
    public function updateBusinessProfile(int $profileId, array $data): array
    {
        return $this->client->put("v2/profiles/{$profileId}/business-profile", $data);
    }

    /**
     * Retrieve a profile by ID
     *
     * @param int $profileId
     * @return array
     */
    public function getProfile(int $profileId): array
    {
        return $this->client->get("v2/profiles/{$profileId}");
    }

    /**
     * List profiles for a user account
     *
     * @return array
     */
    public function listProfiles(): array
    {
        return $this->client->get('v2/profiles');
    }

    /**
     * Create an identification document for a profile
     *
     * @param int $profileId
     * @param array $data Document data
     * @return array
     */
    public function createIdentificationDocument(int $profileId, array $data): array
    {
        return $this->client->post("v1/profiles/{$profileId}/verification-documents", $data);
    }

    /**
     * Create a business director for a profile
     *
     * @param int $profileId
     * @param array $data Director data
     * @return array
     */
    public function createBusinessDirector(int $profileId, array $data): array
    {
        return $this->client->post("v1/profiles/{$profileId}/directors", $data);
    }

    /**
     * List business directors for a profile
     *
     * @param int $profileId
     * @return array
     */
    public function listBusinessDirectors(int $profileId): array
    {
        return $this->client->get("v1/profiles/{$profileId}/directors");
    }

    /**
     * Update business directors for a profile
     *
     * @param int $profileId
     * @param array $data Directors data
     * @return array
     */
    public function updateBusinessDirectors(int $profileId, array $data): array
    {
        return $this->client->put("v1/profiles/{$profileId}/directors", $data);
    }

    /**
     * Create a business ultimate owner for a profile
     *
     * @param int $profileId
     * @param array $data Ultimate owner data
     * @return array
     */
    public function createBusinessUltimateOwner(int $profileId, array $data): array
    {
        return $this->client->post("v1/profiles/{$profileId}/ubos", $data);
    }

    /**
     * List business ultimate owners for a profile
     *
     * @param int $profileId
     * @return array
     */
    public function listBusinessUltimateOwners(int $profileId): array
    {
        return $this->client->get("v1/profiles/{$profileId}/ubos");
    }

    /**
     * Update business ultimate owners for a profile
     *
     * @param int $profileId
     * @param array $data Ultimate owners data
     * @return array
     */
    public function updateBusinessUltimateOwners(int $profileId, array $data): array
    {
        return $this->client->put("v1/profiles/{$profileId}/ubos", $data);
    }

    /**
     * Remove trusted verification from a profile
     *
     * @param int $profileId
     * @return void
     */
    public function removeTrustedVerification(int $profileId): void
    {
        $this->client->delete("v3/profiles/{$profileId}/trusted-verification");
    }

    /**
     * Open an update window for a profile
     *
     * @param int $profileId
     * @return array
     */
    public function openUpdateWindow(int $profileId): array
    {
        return $this->client->post("v1/profiles/{$profileId}/update-window");
    }

    /**
     * Close an update window for a profile
     *
     * @param int $profileId
     * @return void
     */
    public function closeUpdateWindow(int $profileId): void
    {
        $this->client->delete("v1/profiles/{$profileId}/update-window");
    }

    /**
     * Retrieve profile extension requirements for a profile
     *
     * @param int $profileId
     * @return array
     */
    public function getExtensionRequirements(int $profileId): array
    {
        return $this->client->get("v1/profiles/{$profileId}/extension-requirements");
    }

    /**
     * Update profile extensions dynamically
     *
     * @param int $profileId
     * @param array $data Extension data
     * @return array
     */
    public function updateExtensions(int $profileId, array $data): array
    {
        return $this->client->post("v1/profiles/{$profileId}/extensions", $data);
    }

    /**
     * Check profile verification status
     *
     * @param int $profileId
     * @param array $sourceCurrencies Array of currency codes to check
     * @return array
     */
    public function checkVerificationStatus(int $profileId, array $sourceCurrencies): array
    {
        return $this->client->post(
            "v3/profiles/{$profileId}/verification-status/bank-transfer",
            [],
            [],
            ['source_currencies' => implode(',', $sourceCurrencies)]
        );
    }
}
