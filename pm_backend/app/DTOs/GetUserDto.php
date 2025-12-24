<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class GetUserDto extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $avatar = null,
         public bool $isAdmin,
    ) {}
}
