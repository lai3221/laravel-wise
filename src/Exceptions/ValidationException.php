<?php

namespace Lai3221\LaravelWise\Exceptions;

class ValidationException extends WiseException
{
    public function __construct(string $message = "Validation failed", int $code = 422, ?array $errorResponse = null)
    {
        parent::__construct($message, $code, $errorResponse);
    }
} 