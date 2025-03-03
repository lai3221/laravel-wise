<?php

namespace Lai3221\LaravelWise\Exceptions;

use Exception;

class WiseException extends Exception
{
    protected $errorResponse;

    public function __construct(string $message = "", int $code = 0, ?array $errorResponse = null)
    {
        parent::__construct($message, $code);
        $this->errorResponse = $errorResponse;
    }

    public function getErrorResponse(): ?array
    {
        return $this->errorResponse;
    }
} 