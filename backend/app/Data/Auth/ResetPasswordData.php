<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;

class ResetPasswordData
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {}

    public static function from(array $data): self
    {
        return new self(
            email: $data['email'],
            token: $data['token'],
            password: $data['password'],
        );
    }
}
