<?php

namespace App\Data\User;

use App\Models\User;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public ?string $employee_code,
        public string $name,
        public string $email,

        public ?string $phone_number,
        public ?string $birthday,
        public ?int $gender,

        public ?string $join_date,
        public ?string $resign_date,

        public ?string $avatar,
        public ?int $is_active,

        public ?string $created_at,
        public ?string $updated_at,
        public ?string $deleted_at,
    ) {}

    /**
     * Create UserData from User model.
     */
    public static function fromModel(User $user): self
    {
        return new self(
            id: (int) $user->id,
            employee_code: $user->employee_code ?? null,
            name: (string) $user->name,
            email: (string) $user->email,

            phone_number: $user->phone_number ?? null,
            birthday: $user->birthday ? $user->birthday->toDateString() : null,
            gender: $user->gender !== null ? (int) $user->gender : null,

            join_date: $user->join_date ? $user->join_date->toDateString() : null,
            resign_date: $user->resign_date ? $user->resign_date->toDateString() : null,

            avatar: $user->avatar ?? null,
            is_active: $user->is_active !== null ? (int) $user->is_active : null,

            created_at: $user->created_at?->toISOString(),
            updated_at: $user->updated_at?->toISOString(),
            deleted_at: $user->deleted_at?->toISOString(),
        );
    }
}
