# Laravel Wise API Integration

A Laravel package for integrating with the Wise (formerly TransferWise) API.

## Installation

You can install the package via composer:

```bash
composer require lai3221/laravel-wise
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=wise-config
```

Add the following environment variables to your `.env` file:

```env
WISE_ENVIRONMENT=sandbox # or production
WISE_API_KEY=your_api_key_here
```

## Usage

### Basic Usage

```php
use Lai3221\LaravelWise\Services\WiseService;
use Lai3221\LaravelWise\Exceptions\WiseException;
use Lai3221\LaravelWise\Exceptions\AuthenticationException;
use Lai3221\LaravelWise\Exceptions\ValidationException;
use Lai3221\LaravelWise\Exceptions\NotFoundException;
use Lai3221\LaravelWise\Exceptions\ApiException;

class WiseController extends Controller
{
    protected $wiseService;

    public function __construct(WiseService $wiseService)
    {
        $this->wiseService = $wiseService;
    }

    public function getBalances($profileId)
    {
        try {
            $balances = $this->wiseService->getBalances($profileId);
            return response()->json($balances);
        } catch (AuthenticationException $e) {
            return response()->json([
                'error' => 'Authentication failed',
                'message' => $e->getMessage(),
                'details' => $e->getErrorResponse()
            ], 401);
        } catch (NotFoundException $e) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => $e->getMessage(),
                'details' => $e->getErrorResponse()
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->getMessage(),
                'details' => $e->getErrorResponse()
            ], 422);
        } catch (ApiException $e) {
            return response()->json([
                'error' => 'API error',
                'message' => $e->getMessage(),
                'details' => $e->getErrorResponse()
            ], 500);
        }
    }

    public function getBalanceByCurrency($profileId, $currency)
    {
        try {
            $balance = $this->wiseService->getBalanceByCurrency($profileId, $currency);
            return response()->json($balance);
        } catch (WiseException $e) {
            return response()->json([
                'error' => 'Wise API error',
                'message' => $e->getMessage(),
                'details' => $e->getErrorResponse()
            ], $e->getCode());
        }
    }

    public function createTransfer(Request $request)
    {
        $data = [
            'profile' => $request->profile_id,
            'source_currency' => $request->source_currency,
            'target_currency' => $request->target_currency,
            'source_amount' => $request->amount,
            'target_amount' => $request->target_amount,
            'target_account' => $request->target_account,
        ];

        return $this->wiseService->createTransfer($data);
    }
}
```

### Available Methods

#### Balance Operations
- `getBalances(int $profileId): array`
- `getBalanceByCurrency(int $profileId, string $currency): ?array`

#### Transfer Operations
- `createTransfer(array $data): array`
- `getTransfer(int $transferId): array`
- `cancelTransfer(int $transferId): array`

### Exception Handling

The package throws the following exceptions:

- `WiseException`: Base exception class for all Wise-related errors
- `AuthenticationException`: Thrown when API authentication fails (401)
- `ValidationException`: Thrown when request validation fails (422)
- `NotFoundException`: Thrown when a resource is not found (404)
- `ApiException`: Thrown for general API errors (500)

All exceptions contain:
- Message: A human-readable error message
- Code: The HTTP status code
- Error Response: The full error response from the API

Example of catching specific exceptions:

```php
try {
    $balances = $wiseService->getBalances($profileId);
} catch (AuthenticationException $e) {
    // Handle authentication error
    $errorDetails = $e->getErrorResponse();
} catch (ValidationException $e) {
    // Handle validation error
    $errorDetails = $e->getErrorResponse();
} catch (WiseException $e) {
    // Handle any other Wise-related error
    $errorDetails = $e->getErrorResponse();
}
```

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email lai3221@example.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 