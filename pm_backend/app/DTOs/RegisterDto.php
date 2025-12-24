<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class RegisterDto extends Data
{
    public function __construct(
        public string $employee_code,
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone_number = null,
        public ?string $birthday = null, // định dạng 'YYYY-MM-DD'
        public ?int $gender = null       // 1: Male, 2: Female, 3: Other
    ) {}
}