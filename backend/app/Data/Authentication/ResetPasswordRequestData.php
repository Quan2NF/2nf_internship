<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class ResetPasswordRequestData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public string $passwordConfirmation,
        public string $token,
    ) {}
}
