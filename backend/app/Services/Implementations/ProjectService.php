<?php

namespace App\Services\Implementations;

use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\Interfaces\IProjectService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function assignPm(int $projectId, int $pmUserId, int $actorId): bool
    {
        return DB::transaction(function () use ($projectId, $pmUserId, $actorId) {

            $pmRoleId = (int) DB::table('roles')->where('code', 'PM')->value('id');
            if ($pmRoleId <= 0) {
                throw new \RuntimeException("Role 'PM' not found. Please seed RolesSeeder first.");
            }

            // ensure member exists
            $memberId = (int) DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $pmUserId)
                ->value('id');

            if ($memberId <= 0) {
                $memberId = (int) DB::table('project_members')->insertGetId([
                    'project_id' => $projectId,
                    'user_id' => $pmUserId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('project_member_roles as pmr')
                ->join('project_members as pm', 'pm.id', '=', 'pmr.project_member_id')
                ->where('pm.project_id', $projectId)
                ->where('pmr.role_id', $pmRoleId)
                ->delete();

            DB::table('project_member_roles')->updateOrInsert(
                ['project_member_id' => $memberId, 'role_id' => $pmRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );

            if (Schema::hasColumn('projects', 'updated_by')) {
                DB::table('projects')->where('id', $projectId)->update([
                    'updated_by' => $actorId,
                    'updated_at' => now(),
                ]);
            }

            return true;
        });
    }

    public function assignMembers(int $projectId, array $members, int $actorId): bool
    {
        return DB::transaction(function () use ($projectId, $members, $actorId) {

            foreach ($members as $m) {
                $userId = (int) ($m['user_id'] ?? 0);
                $roleCodes = $m['role_codes'] ?? [];

                if ($userId <= 0) {
                    continue;
                }

                if (!is_array($roleCodes)) {
                    $roleCodes = [];
                }

                $memberId = (int) DB::table('project_members')
                    ->where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->value('id');

                if ($memberId <= 0) {
                    $memberId = (int) DB::table('project_members')->insertGetId([
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                foreach ($roleCodes as $code) {
                    $roleId = (int) DB::table('roles')->where('code', (string) $code)->value('id');
                    if ($roleId <= 0) {
                        continue;
                    }

                    DB::table('project_member_roles')->updateOrInsert(
                        ['project_member_id' => $memberId, 'role_id' => $roleId],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            if (Schema::hasColumn('projects', 'updated_by')) {
                DB::table('projects')->where('id', $projectId)->update([
                    'updated_by' => $actorId,
                    'updated_at' => now(),
                ]);
            }

            return true;
        });
    }

    public function getSetting(int $projectId): array
    {
        $wikiId = (int) DB::table('wikis')->where('project_id', $projectId)->value('id');

        if ($wikiId <= 0) {
            $wikiId = (int) DB::table('wikis')->insertGetId([
                'project_id' => $projectId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $content = (string) DB::table('wiki_contents')->where('wiki_id', $wikiId)->value('content');
        if ($content === '') {
            $content = '{}';
        }

        return [
            'wiki_id' => $wikiId,
            'content' => $content,
        ];
    }

    public function updateSetting(int $projectId, string $content, int $actorId): bool
    {
        return DB::transaction(function () use ($projectId, $content, $actorId) {

            $wikiId = (int) DB::table('wikis')->where('project_id', $projectId)->value('id');

            if ($wikiId <= 0) {
                $wikiId = (int) DB::table('wikis')->insertGetId([
                    'project_id' => $projectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('wiki_contents')->updateOrInsert(
                ['wiki_id' => $wikiId],
                ['content' => $content, 'updated_at' => now(), 'created_at' => now()]
            );

            if (Schema::hasColumn('projects', 'updated_by')) {
                DB::table('projects')->where('id', $projectId)->update([
                    'updated_by' => $actorId,
                    'updated_at' => now(),
                ]);
            }

            return true;
        });
    }

    public function getSchedule(int $projectId): array
    {
        $versions = DB::table('versions')
            ->where('project_id', $projectId)
            ->orderBy('id')
            ->get()
            ->map(fn ($v) => [
                'id' => (int) $v->id,
                'name' => (string) $v->name,
                'description' => (string) ($v->description ?? ''),
                'start_date' => $v->start_date,
                'end_date' => $v->end_date,
            ])
            ->toArray();

        return ['versions' => $versions];
    }

    public function updateSchedule(int $projectId, array $versions, int $actorId): bool
    {
        return DB::transaction(function () use ($projectId, $versions, $actorId) {

            foreach ($versions as $v) {
                $id = (int) ($v['id'] ?? 0);

                $payload = [
                    'project_id' => $projectId,
                    'name' => (string) ($v['name'] ?? ''),
                    'description' => $v['description'] ?? null,
                    'start_date' => $v['start_date'] ?? null,
                    'end_date' => $v['end_date'] ?? null,
                    'updated_at' => now(),
                ];

                if ($id > 0) {
                    DB::table('versions')
                        ->where('id', $id)
                        ->where('project_id', $projectId)
                        ->update($payload);
                } else {
                    $payload['created_at'] = now();
                    DB::table('versions')->insert($payload);
                }
            }

            if (Schema::hasColumn('projects', 'updated_by')) {
                DB::table('projects')->where('id', $projectId)->update([
                    'updated_by' => $actorId,
                    'updated_at' => now(),
                ]);
            }

            return true;
        });
    }
}
