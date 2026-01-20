<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    /**
     * @param array<int, array{position_id?:int|null, position_code?:string|null, start_date?:string|null, end_date?:string|null}>|null $positions
     */
    public function __construct(
        public ?string $employee_code,
        public string $name,
        public string $email,
        public ?string $password,
        public ?string $phone_number,
        public ?string $birthday,
        public ?int $gender,
        public ?string $join_date,
        public ?string $resign_date,
        public ?string $avatar,
        public int $is_active = 1,
        public ?array $positions = null,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            $input['employee_code'] ?? null,
            $input['name'],
            $input['email'],
            $input['password'] ?? null,
            $input['phone_number'] ?? null,
            $input['birthday'] ?? null,
            isset($input['gender']) ? (int) $input['gender'] : null,
            $input['join_date'] ?? null,
            $input['resign_date'] ?? null,
            $input['avatar'] ?? null,
            isset($input['is_active']) ? (int) $input['is_active'] : 1,
            $input['positions'] ?? null,
        );
    }
}
