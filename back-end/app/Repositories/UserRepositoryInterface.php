<?php

namespace App\Repositories;

use App\Data\CreateUserData;
use App\Data\ListUsersData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findActiveByEmail(string $email): ?User;

    public function userIsAdmin(User $user): bool;
    public function findByEmail(string $email): ?User;

    /**
     * API05/06: List + filter users (admin-only).
     */
    public function paginateUsers(ListUsersData $filter): LengthAwarePaginator;

    /**
     * API07: Create user and assign positions.
     * Returns an array safe for API responses.
     */
    public function createUser(CreateUserData $data): array;
}
