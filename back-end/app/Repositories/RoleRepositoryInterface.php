<?php

namespace App\Repositories;

use App\Data\CreateRoleData;
use App\Data\ListRolesData;
use App\Data\UpdateRoleData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    public function paginateRoles(ListRolesData $filter): LengthAwarePaginator;

    public function createRole(CreateRoleData $data): array;

    public function updateRole(int $roleId, UpdateRoleData $data): array;

    public function deleteRole(int $roleId): bool;
}
