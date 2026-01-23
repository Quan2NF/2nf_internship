<?php

namespace App\Repositories;

use App\Data\CreateRoleData;
use App\Data\ListRolesData;
use App\Data\UpdateRoleData;
use App\Exceptions\BusinessException;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    public function paginateRoles(ListRolesData $filter): LengthAwarePaginator
    {
        $query = Role::query();

        if ($filter->search) {
            $search = trim($filter->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Sorting: code, name, created_at (prefix '-' for desc)
        $sort = $filter->sort ? trim($filter->sort) : null;
        $direction = 'asc';
        if ($sort && str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = ltrim($sort, '-');
        }

        $allowedSorts = ['code', 'name', 'created_at'];
        if ($sort && in_array($sort, $allowedSorts, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($filter->per_page);
    }

    public function createRole(CreateRoleData $data): array
    {
        return DB::transaction(function () use ($data) {
            if (Role::where('code', $data->code)->exists()) {
                throw new BusinessException('ERR_ROLE_CODE_EXISTS', 422);
            }

            $role = Role::create([
                'code' => $data->code,
                'name' => $data->name,
            ]);

            return [
                'id' => $role->id,
                'code' => $role->code,
                'name' => $role->name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
            ];
        });
    }

    public function updateRole(int $roleId, UpdateRoleData $data): array
    {
        return DB::transaction(function () use ($roleId, $data) {
            $role = Role::query()->whereKey($roleId)->first();
            if (! $role) {
                throw new BusinessException('ERR_ROLE_NOT_FOUND', 404);
            }

            $updates = [];
            if ($data->has('code')) {
                $code = (string) $data->code;
                $exists = Role::where('code', $code)->whereKeyNot($roleId)->exists();
                if ($exists) {
                    throw new BusinessException('ERR_ROLE_CODE_EXISTS', 422);
                }
                $updates['code'] = $code;
            }
            if ($data->has('name')) {
                $updates['name'] = (string) $data->name;
            }

            if (! empty($updates)) {
                $role->fill($updates);
                $role->save();
            }

            return [
                'id' => $role->id,
                'code' => $role->code,
                'name' => $role->name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
            ];
        });
    }

    public function deleteRole(int $roleId): bool
    {
        return DB::transaction(function () use ($roleId) {
            $role = Role::query()->whereKey($roleId)->first();
            if (! $role) {
                throw new BusinessException('ERR_ROLE_NOT_FOUND', 404);
            }

            $inUse = DB::table('project_member_roles')->where('role_id', $roleId)->exists();
            if ($inUse) {
                throw new BusinessException('ERR_ROLE_IN_USE', 422);
            }

            $role->delete();

            return true;
        });
    }
}
