<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findActiveByEmail(string $email): ?User;

    public function userIsAdmin(User $user): bool;
    public function findByEmail(string $email): ?User;
}
