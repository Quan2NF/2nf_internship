<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskPolicy
{
    
    private function isSystemAdmin(User $user): bool
    {
        return DB::table('user_system_roles as usr')
            ->join('roles as r', 'r.id', '=', 'usr.role_id')
            ->where('usr.user_id', $user->id)
            ->where('r.code', 'ADMIN')
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

    
    private function isProjectManager(User $user, int $projectId): bool
    {
        return $this->hasProjectRole($user, $projectId, ['PM', 'PMO']);
    }

    public function view(User $user, Task $task): bool
    {
        if ($this->isSystemAdmin($user)) return true;

        $projectId = (int) $task->project_id;
        return $this->isProjectMember($user, $projectId);
    }

    
    public function create(User $user, int $projectId): bool
    {
        if ($this->isSystemAdmin($user)) return true;

        //PM/PMO tạo task, hoặc DEV cũng tạo được tùy nghiệp vụ
        return $this->hasProjectRole($user, $projectId, ['PM', 'PMO', 'DEV_FE','DEV_BE']);
    }

    public function update(User $user, Task $task): bool
    {
        if ($this->isSystemAdmin($user)) return true;

        $projectId = (int) $task->project_id;

        //PM/PMO sửa mọi task, còn DEV chỉ sửa task của mình 
        return $this->isProjectManager($user, $projectId)
            || $this->hasProjectRole($user, $projectId, ['DEV_FE','DEV_BE', 'QA', 'TEST']);
    }

    public function delete(User $user, Task $task): bool
    {
        if ($this->isSystemAdmin($user)) return true;

        $projectId = (int) $task->project_id;

        //chỉ PM/PMO xoá
        return $this->isProjectManager($user, $projectId);
    }

    public function comment(User $user, Task $task): bool
    {
        if ($this->isSystemAdmin($user)) return true;

        // Member trong project là comment được
        return $this->isProjectMember($user, (int) $task->project_id);
    }
}
