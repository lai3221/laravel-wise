<?php

    namespace Lai3221\LaravelWise\Services;

    use Lai3221\LaravelWise\BaseService;
    use Lai3221\LaravelWise\Client;

    class RateService extends BaseService
    {
        protected Client $client;

        public function __construct(Client $client)
        {
            parent::__construct($client);
            $this->client = $client;
        }

        /**
         * Get exchange rate
         *
         * @param string $sourceCurrency
         * @param string $targetCurrency
         * @param string|null $time
         * @return array
         */
        public function getRate(string $sourceCurrency, string $targetCurrency, ?string $time = null): array
        {
            $endpoint = "v1/rates?source={$sourceCurrency}&target={$targetCurrency}";
            if ($time) {
                $endpoint .= "&time={$time}";
            }
            return $this->client->get($endpoint);
        }

        /**
         * Get historical rates
         *
         * @param string $sourceCurrency
         * @param string $targetCurrency
         * @param string $from
         * @param string $to
         * @return array
         */
        public function getHistoricalRates(string $sourceCurrency, string $targetCurrency, string $from, string $to): array
        {
            $endpoint = "v1/rates?source={$sourceCurrency}&target={$targetCurrency}&from={$from}&to={$to}";
            return $this->client->get($endpoint);
        }
    }
