<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UpdateUserData extends Data
{
    /**
     * @param array<int, array{position_id?:int|null, position_code?:string|null, start_date?:string|null, end_date?:string|null}>|null $positions
     */
    public function __construct(
        public ?string $employee_code,
        public ?string $name,
        public ?string $email,
        public ?string $password,
        public ?string $phone_number,
        public ?string $birthday,
        public ?int $gender,
        public ?string $join_date,
        public ?string $resign_date,
        public ?string $avatar,
        public ?int $is_active,
        public ?array $positions = null,
        /** @var array<int, string> */
        public array $present = [],
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            $input['employee_code'] ?? null,
            $input['name'] ?? null,
            $input['email'] ?? null,
            $input['password'] ?? null,
            $input['phone_number'] ?? null,
            $input['birthday'] ?? null,
            array_key_exists('gender', $input) ? ($input['gender'] === null ? null : (int) $input['gender']) : null,
            $input['join_date'] ?? null,
            $input['resign_date'] ?? null,
            $input['avatar'] ?? null,
            array_key_exists('is_active', $input) ? ($input['is_active'] === null ? null : (int) $input['is_active']) : null,
            array_key_exists('positions', $input) ? $input['positions'] : null,
            array_keys($input),
        );
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->present, true);
    }
}
