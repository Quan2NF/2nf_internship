<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public function __construct(
        private readonly IUserRepository $userRepository
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage);
    }

    public function find(int $id): ?User
    {
        $model = $this->userRepository->find($id);
        return $model instanceof User ? $model : null;
    }

    public function create(array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make((string) $data['password']);
        }

        /** @var User $user */
        $user = $this->userRepository->create($data);
        return $user;
    }

    public function update(int $id, array $data): bool
    {
        if (array_key_exists('password', $data)) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make((string) $data['password']);
            } else {
                unset($data['password']);
            }
        }

        return $this->userRepository->update($id, $data);
    }

    public function softDelete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function updateMyProfile(int $userId, array $data): bool
    {
        if (array_key_exists('password', $data)) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make((string) $data['password']);
            } else {
                unset($data['password']);
            }
        }

        return $this->userRepository->update($userId, $data);
    }

    /**
     * AP05/AP06 - List & filter users.
     */
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $q = User::query();

        if (!empty($filters['keyword'])) {
            $kw = (string) $filters['keyword'];
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%")
                    ->orWhere('employee_code', 'like', "%{$kw}%");
            });
        }

        if (!empty($filters['is_active'])) {
            $q->where('is_active', (int) $filters['is_active']);
        }

        return $q->paginate($perPage);
    }

    /**
     * AP10 - Assign system roles to a user (user_system_roles).
     */
    public function assignSystemRoles(int $userId, array $roleCodes, string $mode = 'sync'): void
    {
        if (empty($roleCodes)) {
            return;
        }

        DB::transaction(function () use ($userId, $roleCodes, $mode) {
            $roleIds = DB::table('roles')->whereIn('code', $roleCodes)->pluck('id')->all();

            if ($mode === 'sync') {
                DB::table('user_system_roles')->where('user_id', $userId)->delete();
            }

            foreach ($roleIds as $rid) {
                DB::table('user_system_roles')->updateOrInsert(
                    ['user_id' => $userId, 'role_id' => $rid],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        });
    }

    /*
     * AP11 - List system roles of a user.
     */
    public function getSystemRoles(int $userId): array
    {
        return DB::table('user_system_roles as usr')
            ->join('roles as r', 'r.id', '=', 'usr.role_id')
            ->where('usr.user_id', $userId)
            ->select(['r.id', 'r.code', 'r.name'])
            ->orderBy('r.code')
            ->get()
            ->map(fn ($r) => ['id' => (int) $r->id, 'code' => $r->code, 'name' => $r->name])
            ->all();
    }

    //  Project roles 

    public function assignRolesInProject(int $projectId, int $userId, array $roleCodes): void
    {
        if (empty($roleCodes)) {
            return;
        }

        DB::transaction(function () use ($projectId, $userId, $roleCodes) {
            $projectMemberId = DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->value('id');

            if (!$projectMemberId) {
                $projectMemberId = DB::table('project_members')->insertGetId([
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $roleIds = DB::table('roles')->whereIn('code', $roleCodes)->pluck('id')->all();

            DB::table('project_member_roles')
                ->where('project_member_id', $projectMemberId)
                ->delete();

            foreach ($roleIds as $rid) {
                DB::table('project_member_roles')->updateOrInsert(
                    ['project_member_id' => $projectMemberId, 'role_id' => $rid],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        });
    }

    public function getRolesInProject(int $projectId, int $userId): array
    {
        return DB::table('project_members as pm')
            ->join('project_member_roles as pmr', 'pmr.project_member_id', '=', 'pm.id')
            ->join('roles as r', 'r.id', '=', 'pmr.role_id')
            ->where('pm.project_id', $projectId)
            ->where('pm.user_id', $userId)
            ->select(['r.id', 'r.code', 'r.name'])
            ->get()
            ->map(fn ($r) => ['id' => (int) $r->id, 'code' => $r->code, 'name' => $r->name])
            ->all();
    }
}
