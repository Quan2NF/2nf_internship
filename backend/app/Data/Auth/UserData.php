<?php

namespace App\Data\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $employee_code,
        public string $name,
        public string $email,
        public ?string $phone_number,
        public ?string $birthday,
        public ?int $gender,
        public ?string $gender_label,
        public ?string $join_date,
        public ?string $resign_date,
        public ?string $avatar,
        public int $is_active,
        public string $is_active_status,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            employee_code: $user->employee_code,
            name: $user->name,
            email: $user->email,
            phone_number: $user->phone_number,
            birthday: $user->birthday?->format('Y-m-d'),
            gender: $user->gender,
            gender_label: $user->gender_label,
            join_date: $user->join_date?->format('Y-m-d'),
            resign_date: $user->resign_date?->format('Y-m-d'),
            avatar: $user->avatar,
            is_active: $user->is_active,
            is_active_status: $user->is_active_status,
            created_at: $user->created_at->toIso8601String(),
            updated_at: $user->updated_at->toIso8601String(),
        );
    }
}