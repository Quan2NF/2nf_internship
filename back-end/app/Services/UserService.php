<?php

namespace App\Services;

use App\Data\AssignPositionsData;
use App\Data\CreateUserData;
use App\Data\ListUsersData;
use App\Data\UpdateUserData;
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

    public function createUser(CreateUserData $data): array
    {
        $result = $this->users->createUser($data);

        return $result;
    }

    public function updateUser(int $userId, UpdateUserData $data): array
    {
        return $this->users->updateUser($userId, $data);
    }

    public function deleteUser(int $userId): bool
    {
        return $this->users->deleteUser($userId);
    }

    public function assignPositions(int $userId, AssignPositionsData $data): array
    {
        return $this->users->assignPositions($userId, $data);
    }
}
