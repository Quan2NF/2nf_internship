<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectPolicy
{
    private function isOwner(User $user, Project $project): bool
    {
        return (int)$project->user_id === (int)$user->id;
    }

    private function isMember(User $user, Project $project): bool
    {
        return DB::table('project_members')
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function hasRole(User $user, Project $project, array $roleCodes): bool
    {
        return DB::table('project_members as pm')
            ->join('project_member_roles as pmr', 'pmr.project_member_id', '=', 'pm.id')
            ->join('roles as r', 'r.id', '=', 'pmr.role_id')
            ->where('pm.project_id', $project->id)
            ->where('pm.user_id', $user->id)
            ->whereIn('r.code', $roleCodes)
            ->exists();
    }

    public function view(User $user, Project $project): bool
    {
        // owner OR member
        return $this->isOwner($user, $project) || $this->isMember($user, $project);
    }

    public function create(User $user): bool
    {
        // ai đăng nhập cũng tạo được project (tuỳ bạn)
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        // owner OR role quản lý trong project
        return $this->isOwner($user, $project) || $this->hasRole($user, $project, ['PM', 'ADMIN']);
    }

    public function delete(User $user, Project $project): bool
    {
        // thường chỉ owner hoặc ADMIN
        return $this->isOwner($user, $project) || $this->hasRole($user, $project, ['ADMIN']);
    }
}
