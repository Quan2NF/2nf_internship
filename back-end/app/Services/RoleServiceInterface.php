<?php

namespace App\Services;

use App\Data\CreateRoleData;
use App\Data\ListRolesData;
use App\Data\UpdateRoleData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoleServiceInterface
{
    public function listRoles(ListRolesData $filter): LengthAwarePaginator;

    public function createRole(CreateRoleData $data): array;

    public function updateRole(int $roleId, UpdateRoleData $data): array;

    public function deleteRole(int $roleId): bool;
}
