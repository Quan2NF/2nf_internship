<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class UpdateUserDto extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $role
    ) {}

    public function toArray(): array {
        $data = [];

        if ($this->name !== null) $data['name'] = $this->name;
        if ($this->email !== null) $data['email'] = $this->email;
        if ($this->phone !== null) $data['phone'] = $this->phone;
        if ($this->role !== null) $data['role'] = $this->role;

        return $data;
    }
}