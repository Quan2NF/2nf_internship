<?php

namespace App\Repositories;

use App\Data\CreateUserData;
use App\Data\ListUsersData;
use App\Data\UpdateUserData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Data\AssignPositionsData;

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

    /**
     * API08: Edit user and (optionally) re-assign positions.
     */
    public function updateUser(int $userId, UpdateUserData $data): array;

    /**
     * API09: Soft delete a user.
     */
    public function deleteUser(int $userId): bool;

    /**
     * API10: Assign positions to a user.
     */
    public function assignPositions(int $userId, AssignPositionsData $data): array;

    /**
     * API11: List roles (positions) of a user.
     */
    public function listUserPositions(int $userId): array;
}
