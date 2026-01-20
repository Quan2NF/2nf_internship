<?php

namespace App\Service;

use App\Models\Role;
use App\Models\Project;
use App\Enums\ResponseCode;
use App\Models\ProjectMember;
use App\Data\Common\KeyOnlyData;
use App\Data\Project\AssignPMData;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Data\Project\ProjectSchedule;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectResponseData;
use App\Data\Project\ProjectSettingsData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\AssignMembersToProjectData;
use App\Contracts\Service\ProjectServiceInterface;

class ProjectService implements ProjectServiceInterface
{
    public function getFilteredList(ProjectListFilterData $data): ApiResponseData
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Project::query();

        if (! $user->hasAnyPosition(['ADMIN', 'PMO'])) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        // Keyword search (code or name)
        if ($data->keyword) {
            $query->where(function ($q) use ($data) {
                $q->where('code', 'like', "%{$data->keyword}%")
                ->orWhere('name', 'like', "%{$data->keyword}%");
            });
        }

        // Status filter
        if ($data->status) {
            $query->where('status', $data->status->value);
        }

        // Start date filter
        if ($data->start_date) {
            $query->where('start_date', '>=', $data->start_date);
        }

        // End date filter
        if ($data->end_date) {
            $query->where('end_date', '<=', $data->end_date);
        }

        // Active flag
        if (!is_null($data->is_active)) {
            $query->where('is_active', $data->is_active);
        }

        // Public flag
        if (!is_null($data->is_public)) {
            $query->where('is_public', $data->is_public);
        }

        // Pagination
        $projects = $query->paginate(
            perPage: $data->per_page,
            page: $data->page
        );

        return ApiResponse::from(ResponseCode::SUCCESS, $projects);
    }

    public function create(ProjectRequestData $data): ApiResponseData
    {
        $project = Project::query()->create([
            ...$data->toArray(),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS, ['id' => $project->id]);
    }

    public function view(Project $project): ApiResponseData
    {
        return ApiResponse::from(ResponseCode::SUCCESS, ProjectResponseData::from($project));
    }

    public function update(Project $project, ProjectRequestData $data): ApiResponseData
    {
        $project->update([
            ...array_filter($data->toArray(), fn ($v) => $v !== null),
            'updated_by' => Auth::user()->id
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS, ProjectResponseData::from($project));
    }

    public function delete(Project $project): ApiResponseData
    {
        $project->delete();

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function assignPM(Project $project, AssignPMData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {

            // Ensure user is a member of this project
            $member = ProjectMember::query()->firstOrCreate([
                'project_id' => $project->id,
                'user_id'    => $data->pm_id,
            ]);

            // Resolve PM role
            $pmRole = Role::query()
                ->where('code', 'PM')
                ->firstOrFail();

            // Remove PM role from other members in this project
            ProjectMember::query()
                ->where('project_id', $project->id)
                ->whereHas('roles', fn ($q) => $q->where('roles.code', 'PM'))
                ->each(fn ($m) => $m->roles()->detach($pmRole->id));

            // Assign PM role to selected member
            $member->roles()->syncWithoutDetaching([$pmRole->id]);

            return ApiResponse::from(ResponseCode::SUCCESS, [
                'project_id' => $project->id,
                'pm_id'      => $data->pm_id,
            ]);
        });
    }

    public function assignMembers(Project $project, AssignMembersToProjectData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {
            foreach ($data->user_ids as $userId) {
                ProjectMember::query()->firstOrCreate([
                    'project_id' => $project->id,
                    'user_id'    => $userId,
                ]);
            }

            return ApiResponse::from(ResponseCode::SUCCESS, [
                'project_id' => $project->id,
                'member_ids' => ProjectMember::query()
                ->where('project_id', $project->id)
                ->pluck('user_id')
                ->values(),
            ]);
        });
    }

    public function getSettings(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateSettings(ProjectSettingsData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getSchedule(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateSchedule(ProjectSchedule $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}