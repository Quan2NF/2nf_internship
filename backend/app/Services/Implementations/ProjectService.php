<?php

namespace App\Services\Implementations;

use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\Interfaces\IProjectService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProjectService implements IProjectService
{
    public function __construct(
        private readonly IProjectRepository $projectRepository
    ) {}

    public function listProjects(int $actorId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $canViewAll = $this->isSystemRole($actorId, ['ADMIN', 'PMO']);

        return $this->projectRepository->paginateVisibleProjects(
            userId: $actorId,
            canViewAll: $canViewAll,
            filters: $filters,
            perPage: $perPage
        );
    }

    public function create(array $data, int $actorId): Project
    {
        return DB::transaction(function () use ($data, $actorId) {

            $project = $this->projectRepository->createProject([
                ...$data,
                'created_by' => $actorId,
                'updated_by' => $actorId,
            ]);

            // add creator into project_members
            $projectMemberId = DB::table('project_members')->insertGetId([
                'project_id' => $project->id,
                'user_id'    => $actorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // assign PM role to creator (default)
            $pmRoleId = (int) DB::table('roles')->where('code', 'PM')->value('id');
            if ($pmRoleId > 0) {
                DB::table('project_member_roles')->updateOrInsert(
                    ['project_member_id' => $projectMemberId, 'role_id' => $pmRoleId],
                    ['updated_at' => now(), 'created_at' => now()]
                );
            }

            return $project;
        });
    }

    public function update(int $projectId, array $data, int $actorId): bool
    {
        return $this->projectRepository->updateProject($projectId, [
            ...$data,
            'updated_by' => $actorId,
        ]);
    }

    public function delete(int $projectId): void
    {
        $this->projectRepository->delete($projectId); // soft delete nếu model dùng SoftDeletes
    }

    private function isSystemRole(int $userId, array $roleCodes): bool
    {
        return DB::table('user_system_roles as usr')
            ->join('roles as r', 'r.id', '=', 'usr.role_id')
            ->where('usr.user_id', $userId)
            ->whereIn('r.code', $roleCodes)
            ->exists();
    }
}
