<?php

namespace App\Services;

use App\Data\AssignPositionsData;
use App\Data\CreateUserData;
use App\Data\ListUsersData;
use App\Data\UpdateUserData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * API05/06: List + filter users (admin-only).
     */
    public function listUsers(ListUsersData $filter): LengthAwarePaginator;

    /**
     * API07: Create user with one or many roles (admin-only).
     */
    public function createUser(CreateUserData $data): array;

    /**
     * API08: Edit user + roles (admin-only).
     */
    public function updateUser(int $userId, UpdateUserData $data): array;

    /**
     * API09: Soft delete a user (admin-only).
     */
    public function deleteUser(int $userId): bool;
    
    /**
     * API10: Assign positions to a user (admin-only).
     */
    public function assignPositions(int $userId, AssignPositionsData $data): array;

    /**
     * API11: List roles (positions) of a user (admin-only).
     */
    public function listUserPositions(int $userId): array;
}
