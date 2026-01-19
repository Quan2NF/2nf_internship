<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

class CreateUserResponseData extends Data
{
    public function __construct(
        public int $id,
    ) {}
}
