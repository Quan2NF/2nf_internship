<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use App\Models\User;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->email
        );
    }
}
