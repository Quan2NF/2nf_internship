<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class LoginResponseData extends Data
{
    public function __construct(
        public string $employee_code,
        public string $name,
        public string $email,
        public string $token
    ) {}
}
