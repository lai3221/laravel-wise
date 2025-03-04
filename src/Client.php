<?php

namespace Lai3221\LaravelWise;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Lai3221\LaravelWise\Exceptions\ApiException;
use Lai3221\LaravelWise\Exceptions\AuthenticationException;
use Lai3221\LaravelWise\Exceptions\NotFoundException;
use Lai3221\LaravelWise\Exceptions\ValidationException;

class Client
{
    /**
     * Base URL for the API
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * API Key for authentication
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API Version
     *
     * @var string
     */
    protected $version;

    /**
     * Request timeout in seconds
     *
     * @var int
     */
    protected $timeout;

    /**
     * Default headers for all requests
     *
     * @var array
     */
    protected $headers;

    /**
     * Create a new Client instance
     */
    public function __construct()
    {
        $this->baseUrl = config('wise.base_url.' . config('wise.environment'));
        $this->apiKey = config('wise.api_key');
        $this->version = config('wise.version');
        $this->timeout = config('wise.timeout');
        $this->headers = [
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get the full URL for the API endpoint
     *
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    protected function getUrl(string $endpoint, array $params = []): string
    {
        $url = "{$this->baseUrl}/{$endpoint}";

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Make a GET request
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param bool $cache
     * @param int $cacheTime
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    public function get(string $endpoint, array $params = [], array $headers = [], bool $cache = false, int $cacheTime = 300): array
    {
        if ($cache) {
            $cacheKey = "wise_get_" . md5($endpoint . json_encode($params));
            return Cache::remember($cacheKey, $cacheTime, function () use ($endpoint, $params, $headers) {
                return $this->makeRequest('get', $endpoint, $params, null, $headers);
            });
        }
        return $this->makeRequest('get', $endpoint, $params, null, $headers);
    }

    /**
     * Make a POST request
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    public function post(string $endpoint, array $data, array $headers = []): array
    {
        return $this->makeRequest('post', $endpoint, [], $data, $headers);
    }

    /**
     * Make a PUT request
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    public function put(string $endpoint, array $data, array $headers = []): array
    {
        return $this->makeRequest('put', $endpoint, [], $data, $headers);
    }

    /**
     * Make a PATCH request
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    public function patch(string $endpoint, array $data, array $headers = []): array
    {
        return $this->makeRequest('patch', $endpoint, [], $data, $headers);
    }

    /**
     * Make a DELETE request
     *
     * @param string $endpoint
     * @param array $headers
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    public function delete(string $endpoint, array $headers = []): array
    {
        return $this->makeRequest('delete', $endpoint, [], null, $headers);
    }

    /**
     * Make the actual HTTP request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @param array|null $data
     * @param array $additionalHeaders
     * @return array
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    protected function makeRequest(string $method, string $endpoint, array $params = [], ?array $data = null, array $additionalHeaders = []): array
    {
        $url = $this->getUrl($endpoint, $params);
        $headers = array_merge($this->headers, $additionalHeaders);

        $request = Http::withHeaders($headers)
            ->timeout($this->timeout)
            ->retry(3, 100); // Retry failed requests up to 3 times with 100ms delay

        if (in_array($method, ['post', 'put', 'patch']) && $data !== null) {
            $request->withBody(json_encode($data), 'application/json');
        }

        $response = $request->$method($url);
        $responseBody = $response->json();

        if ($response->successful()) {
            return $responseBody;
        }

        $this->handleRequestError($response->status(), $responseBody);
    }

    /**
     * Handle request errors and throw appropriate exceptions
     *
     * @param int $statusCode
     * @param array|null $responseBody
     * @throws ApiException|AuthenticationException|ValidationException|NotFoundException
     */
    protected function handleRequestError(int $statusCode, ?array $responseBody): void
    {
        $message = $responseBody['error'] ?? $responseBody['message'] ?? 'Unknown error';
        $errorCode = $responseBody['code'] ?? null;
        $details = $responseBody['details'] ?? null;

        switch ($statusCode) {
            case 401:
                throw new AuthenticationException($message, $statusCode, $responseBody);
            case 404:
                throw new NotFoundException($message, $statusCode, $responseBody);
            case 422:
                throw new ValidationException($message, $statusCode, $responseBody);
            default:
                throw new ApiException($message, $statusCode, $responseBody);
        }
    }
}
