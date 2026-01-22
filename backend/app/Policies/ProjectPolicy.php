<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectPolicy
{
    private function hasSystemRole(User $user, array $roleCodes): bool
    {
        return DB::table('user_system_roles as usr')
            ->join('roles as r', 'r.id', '=', 'usr.role_id')
            ->where('usr.user_id', $user->id)
            ->whereIn('r.code', $roleCodes)
            ->exists();
    }

    private function isProjectMember(User $user, Project $project): bool
    {
        return DB::table('project_members')
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function hasProjectRole(User $user, Project $project, array $roleCodes): bool
    {
        return DB::table('project_members as pm')
            ->join('project_member_roles as pmr', 'pmr.project_member_id', '=', 'pm.id')
            ->join('roles as r', 'r.id', '=', 'pmr.role_id')
            ->where('pm.project_id', $project->id)
            ->where('pm.user_id', $user->id)
            ->whereIn('r.code', $roleCodes)
            ->exists();
    }

    // API16/17
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        if ($this->hasSystemRole($user, ['ADMIN', 'PMO'])) {
            return true;
        }
        return $this->isProjectMember($user, $project);
    }

    // API18: chỉ ADMIN/PMO được tạo
    public function create(User $user): bool
    {
        return $this->hasSystemRole($user, ['ADMIN', 'PMO']);
    }

    // API19: ADMIN/PMO hoặc PM trong project
    public function update(User $user, Project $project): bool
    {
        if ($this->hasSystemRole($user, ['ADMIN', 'PMO'])) {
            return true;
        }
        return $this->hasProjectRole($user, $project, ['PM']);
    }

    // API20: ADMIN/PMO
    public function delete(User $user, Project $project): bool
    {
        return $this->hasSystemRole($user, ['ADMIN', 'PMO']);
    }
}
