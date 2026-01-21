<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        $user = $this->userRepository->find($id);
        return $user instanceof User ? $user : null;
    }

    public function create(array $data): User
    {
        // Tránh lưu plain password
        if (isset($data['password'])) {
            $data['password'] = Hash::make((string) $data['password']);
        }

        $created = $this->userRepository->create($data);
        if (!$created instanceof User) {
            throw new \RuntimeException('Failed to create user.');
        }

        return $created;
    }

    public function update(int $id, array $data): bool
    {
        // Nếu có update password
        if (isset($data['password'])) {
            $data['password'] = Hash::make((string) $data['password']);
        }

        return $this->userRepository->update($id, $data);
    }

    public function softDelete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function updateMyProfile(int $userId, array $data): bool
    {
        // Chặn các field nhạy cảm (tuỳ bạn)
        unset($data['password'], $data['email_verified_at'], $data['remember_token']);

        return $this->userRepository->update($userId, $data);
    }

    public function assignRolesInProject(int $projectId, int $userId, array $roleCodes): void
    {
        DB::transaction(function () use ($projectId, $userId, $roleCodes) {
            // 1) ensure project_member exists
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

            // 2) get role ids
            $roleIds = DB::table('roles')
                ->whereIn('code', $roleCodes)
                ->pluck('id')
                ->all();

            // 3) reset old roles then insert new roles
            DB::table('project_member_roles')
                ->where('project_member_id', $projectMemberId)
                ->delete();

            $rows = array_map(fn ($roleId) => [
                'project_member_id' => $projectMemberId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ], $roleIds);

            if (!empty($rows)) {
                DB::table('project_member_roles')->insert($rows);
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
