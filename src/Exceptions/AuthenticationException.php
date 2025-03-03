<?php

namespace Lai3221\LaravelWise\Exceptions;

class AuthenticationException extends WiseException
{
    public function __construct(string $message = "Authentication failed", int $code = 401, ?array $errorResponse = null)
    {
        parent::__construct($message, $code, $errorResponse);
    }
} 