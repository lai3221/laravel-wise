<?php

     namespace Lai3221\LaravelWise\Services;

     use Lai3221\LaravelWise\BaseService;
     use Lai3221\LaravelWise\Client;

     class QuoteService extends BaseService
     {
         protected Client $client;

         public function __construct(Client $client)
         {
             parent::__construct($client);
             $this->client = $client;
         }

         /**
          * Create a quote
          *
          * @param array $data Quote data including:
          *                    - sourceCurrency (required)
          *                    - targetCurrency (required)
          *                    - sourceAmount or targetAmount (one is required)
          *                    - profile (required)
          * @return array
          */
         public function createUnauthenticatedQuote(array $data): array
         {
             return $this->client->post('v3/quotes', $data);
         }

         /**
          * Create a quote
          *
          * @param int $profileId
          * @param array $data Quote data including:
          *                    - sourceCurrency (required)
          *                    - targetCurrency (required)
          *                    - sourceAmount or targetAmount (one is required)
          *                    - profile (required)
          * @return array
          */
         public function createAuthenticatedQuote(int $profileId, array $data): array
         {
             return $this->client->post("v3/profiles/{$profileId}/quotes", $data);
         }

         /**
          * Retrieve a quote by ID
          *
          * @param int $profileId
          * @param string $quoteId
          * @return array
          */
         public function getQuote(int $profileId, string $quoteId): array
         {
             return $this->client->get("v3/profiles/{$profileId}/quotes/{$quoteId}");
         }

         /**
          * Accept a quote
          *
          * @param int $profileId
          * @param string $quoteId
          * @return array
          */
         public function updateQuote(int $profileId, string $quoteId): array
         {
             return $this->client->patch("v3/profiles/{$profileId}/quotes/{$quoteId}");
         }
     }
