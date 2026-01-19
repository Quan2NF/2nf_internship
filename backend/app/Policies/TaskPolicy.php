<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskPolicy
{
    private function isProjectOwner(User $user, int $projectId): bool
    {
        return DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function isProjectMember(User $user, int $projectId): bool
    {
        return DB::table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function hasProjectRole(User $user, int $projectId, array $roleCodes): bool
    {
        return DB::table('project_members as pm')
            ->join('project_member_roles as pmr', 'pmr.project_member_id', '=', 'pm.id')
            ->join('roles as r', 'r.id', '=', 'pmr.role_id')
            ->where('pm.project_id', $projectId)
            ->where('pm.user_id', $user->id)
            ->whereIn('r.code', $roleCodes)
            ->exists();
    }

    public function view(User $user, Task $task): bool
    {
        return $this->isProjectOwner($user, (int)$task->project_id)
            || $this->isProjectMember($user, (int)$task->project_id);
    }

    public function create(User $user, int $projectId): bool
    {
        // ai là member thì tạo task được, hoặc chỉ role DEV/PM tuỳ bạn
        return $this->isProjectOwner($user, $projectId)
            || $this->hasProjectRole($user, $projectId, ['PM', 'DEV', 'ADMIN']);
    }

    public function update(User $user, Task $task): bool
    {
        // owner/PM/ADMIN update được
        return $this->isProjectOwner($user, (int)$task->project_id)
            || $this->hasProjectRole($user, (int)$task->project_id, ['PM', 'ADMIN']);
    }

    public function delete(User $user, Task $task): bool
    {
        // thường chỉ PM/ADMIN/Owner
        return $this->isProjectOwner($user, (int)$task->project_id)
            || $this->hasProjectRole($user, (int)$task->project_id, ['ADMIN']);
    }
}
