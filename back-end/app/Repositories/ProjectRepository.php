<?php

namespace App\Repositories;

use App\Data\CreateProjectData;
use App\Data\ListProjectsData;
use App\Data\UpdateProjectData;
use App\Exceptions\BusinessException;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function paginateProjects(ListProjectsData $filter): LengthAwarePaginator
    {
        $query = Project::query();

        if ($filter->search) {
            $q = trim($filter->search);
            $query->where(function ($q2) use ($q) {
                $q2->where('code', 'like', "%{$q}%")
                   ->orWhere('name', 'like', "%{$q}%");
            });
        }

        if ($filter->status) {
            $query->where('status', $filter->status);
        }

        $sort = $filter->sort ? trim($filter->sort) : null;
        $direction = 'asc';
        if ($sort && str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = ltrim($sort, '-');
        }

        $allowed = ['code', 'name', 'planned_start_date', 'planned_end_date', 'created_at'];
        if ($sort && in_array($sort, $allowed, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($filter->per_page);
    }

    public function findById(int $id)
    {
        return Project::query()->find($id);
    }

    public function createProject(CreateProjectData $data): array
    {
        return DB::transaction(function () use ($data) {
            if (Project::where('code', $data->code)->exists()) {
                throw new BusinessException('ERR_PROJECT_CODE_EXISTS', 422);
            }

            $project = Project::create([
                'code' => $data->code,
                'name' => $data->name,
                'planned_start_date' => $data->kickoff_date,
                'planned_end_date' => date('Y-m-d', strtotime("{$data->kickoff_date} +{$data->duration_days} days")),
                'description' => $data->description,
                'status' => 'planning',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            return [
                'id' => $project->id,
                'code' => $project->code,
                'name' => $project->name,
            ];
        });
    }

    public function updateProject(int $id, UpdateProjectData $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $project = Project::find($id);
            if (! $project) {
                throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
            }

            $updates = [];
            if ($data->has('code')) {
                $code = (string)$data->code;
                $exists = Project::where('code', $code)->whereKeyNot($id)->exists();
                if ($exists) throw new BusinessException('ERR_PROJECT_CODE_EXISTS', 422);
                $updates['code'] = $code;
            }
            if ($data->has('name')) $updates['name'] = $data->name;
            if ($data->has('kickoff_date')) $updates['planned_start_date'] = $data->kickoff_date;
            if ($data->has('duration_days')) $updates['planned_end_date'] = date('Y-m-d', strtotime("{$project->planned_start_date} +{$data->duration_days} days"));
            if ($data->has('description')) $updates['description'] = $data->description;

            if (! empty($updates)) {
                $project->fill($updates);
                $project->save();
            }

            return [
                'id' => $project->id,
                'code' => $project->code,
                'name' => $project->name,
            ];
        });
    }

    public function deleteProject(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $project = Project::find($id);
            if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
            $project->delete();
            return true;
        });
    }

    public function assignPm(int $projectId, int $pmId): bool
    {
        return DB::transaction(function () use ($projectId, $pmId) {
            $project = Project::find($projectId);
            if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);

            // Ensure project_member row exists (insert if not)
            $memberId = DB::table('project_members')->where('project_id', $projectId)->where('user_id', $pmId)->value('id');
            if (! $memberId) {
                $memberId = DB::table('project_members')->insertGetId([
                    'project_id' => $projectId,
                    'user_id' => $pmId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Find role id for PM (try code then name, case-insensitive fallback)
            $pmRoleId = DB::table('roles')->where('code', 'PM')->orWhere('name', 'PM')->value('id');
            if (! $pmRoleId) {
                $pmRoleId = DB::table('roles')->whereRaw('LOWER(code) = ?', ['pm'])->orWhereRaw('LOWER(name) = ?', ['pm'])->value('id');
            }
            if (! $pmRoleId) {
                throw new BusinessException('ERR_PM_ROLE_NOT_FOUND', 500);
            }

            // Remove existing PM assignments on this project
            $existingPmMemberIds = DB::table('project_member_roles')
                ->join('project_members', 'project_member_roles.project_member_id', '=', 'project_members.id')
                ->where('project_members.project_id', $projectId)
                ->where('project_member_roles.role_id', $pmRoleId)
                ->pluck('project_member_roles.project_member_id')
                ->toArray();

            if (! empty($existingPmMemberIds)) {
                DB::table('project_member_roles')->whereIn('project_member_id', $existingPmMemberIds)->where('role_id', $pmRoleId)->delete();
            }

            // Assign PM role to this project_member
            DB::table('project_member_roles')->updateOrInsert([
                'project_member_id' => $memberId,
                'role_id' => $pmRoleId,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return true;
        });
    }

    public function assignMembers(int $projectId, array $memberIds): bool
    {
        return DB::transaction(function () use ($projectId, $memberIds) {
            $project = Project::find($projectId);
            if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);

            foreach ($memberIds as $uid) {
                $exists = DB::table('project_members')->where('project_id', $projectId)->where('user_id', $uid)->value('id');
                if (! $exists) {
                    DB::table('project_members')->insert([
                        'project_id' => $projectId,
                        'user_id' => $uid,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return true;
        });
    }

    public function setSettings(int $projectId, array $settings): array
    {
        $project = Project::find($projectId);
        if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
        $project->settings = $settings;
        $project->save();
        return $project->settings ?? [];
    }

    public function updateSettings(int $projectId, array $settings): array
    {
        $project = Project::find($projectId);
        if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
        $existing = (array) ($project->settings ?? []);
        $merged = array_replace_recursive($existing, $settings);
        $project->settings = $merged;
        $project->save();
        return $project->settings;
    }

    public function getSchedule(int $projectId): array
    {
        $project = Project::find($projectId);
        if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
        return $project->settings['schedule'] ?? [];
    }

    public function updateSchedule(int $projectId, array $schedule): array
    {
        $project = Project::find($projectId);
        if (! $project) throw new BusinessException('ERR_PROJECT_NOT_FOUND', 404);
        $settings = (array) ($project->settings ?? []);
        $settings['schedule'] = $schedule;
        $project->settings = $settings;
        $project->save();
        return $settings['schedule'];
    }
}
