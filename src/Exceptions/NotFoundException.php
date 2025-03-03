<?php

namespace Lai3221\LaravelWise\Exceptions;

class NotFoundException extends WiseException
{
    public function __construct(string $message = "Resource not found", int $code = 404, ?array $errorResponse = null)
    {
        parent::__construct($message, $code, $errorResponse);
    }
} 