<?php

namespace App\Services;

use App\Data\CreateRoleData;
use App\Data\ListRolesData;
use App\Data\UpdateRoleData;
use App\Repositories\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService implements RoleServiceInterface
{
    public function __construct(
        private readonly RoleRepositoryInterface $roles
    ) {}

    public function listRoles(ListRolesData $filter): LengthAwarePaginator
    {
        return $this->roles->paginateRoles($filter);
    }

    public function createRole(CreateRoleData $data): array
    {
        return $this->roles->createRole($data);
    }

    public function updateRole(int $roleId, UpdateRoleData $data): array
    {
        return $this->roles->updateRole($roleId, $data);
    }

    public function deleteRole(int $roleId): bool
    {
        return $this->roles->deleteRole($roleId);
    }
}
