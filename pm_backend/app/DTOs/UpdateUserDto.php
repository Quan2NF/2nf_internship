<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class UpdateUserDto extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public array $role = []
    ) {}

    public function toArray(): array {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
        ];
    }
}