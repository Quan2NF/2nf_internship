<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class LoginRequestData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
