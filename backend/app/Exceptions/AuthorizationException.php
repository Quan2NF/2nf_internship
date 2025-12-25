<?php

namespace App\Exceptions;

class AuthorizationException extends ApiException
{
    public function __construct(
        string $message = 'Unauthorized action',
        array $errors = []
    ) {
        parent::__construct($message, 403, $errors);
    }
}
