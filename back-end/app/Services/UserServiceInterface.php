<?php

namespace App\Services;

use App\Data\CreateUserData;
use App\Data\ListUsersData;
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
}
