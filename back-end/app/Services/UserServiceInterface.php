<?php

namespace App\Services;

use App\Data\ListUsersData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * API05/06: List + filter users (admin-only).
     */
    public function listUsers(ListUsersData $filter): LengthAwarePaginator;
}
