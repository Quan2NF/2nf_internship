<?php

namespace App\Services;

use App\Data\ListUsersData;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function listUsers(ListUsersData $filter): LengthAwarePaginator
    {
        return $this->users->paginateUsers($filter);
    }
}
