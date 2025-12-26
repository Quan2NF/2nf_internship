<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class ForgotPasswordRequestData extends Data
{
    public function __construct(
        public string $email
    ) {}
}
