<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function findActiveByEmail(string $email): ?User
    {
        return User::where('email', $email)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();
    }

    public function userIsAdmin(User $user): bool
    {
        return $user->positions()->where('is_admin', true)->exists();
    }
    public function findByEmail(string $email): ?User
  {
    return User::where('email', $email)
        ->whereNull('deleted_at')
        ->first();
}
}