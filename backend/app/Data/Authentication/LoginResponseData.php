<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class LoginResponseData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $avatar,
        public string $token
    ) {}
}
