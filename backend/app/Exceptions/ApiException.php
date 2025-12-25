<?php

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    protected int $status;
    protected array $errors;

    public function __construct(
        string $message,
        int $status,
        array $errors = []
    ) {
        parent::__construct($message);
        $this->status = $status;
        $this->errors = $errors;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
