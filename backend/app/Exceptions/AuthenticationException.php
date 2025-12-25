<?php

namespace App\Exceptions;

class AuthenticationException extends ApiException
{
    public function __construct(
        string $message = 'Authentication failed',
        array $errors = []
    ) {
        parent::__construct($message, 401, $errors);
    }
}
