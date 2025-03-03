<?php

namespace Lai3221\LaravelWise\Exceptions;

class ApiException extends WiseException
{
    public function __construct(string $message = "API request failed", int $code = 500, ?array $errorResponse = null)
    {
        parent::__construct($message, $code, $errorResponse);
    }
} 