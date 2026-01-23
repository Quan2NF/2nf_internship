<?php

namespace App\Service;

use App\Models\Role;
use App\Models\User;
use App\Models\Wiki;
use App\Models\Project;
use App\Models\Document;
use App\Enums\ResponseCode;
use App\Models\WikiContent;
use App\Models\ProjectMember;
use App\Data\Common\KeyOnlyData;
use App\Data\Project\AssignPMData;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectResponseData;
use App\Data\Project\ProjectScheduleData;
use App\Data\Project\ProjectSettingsData;
use App\Data\User\DetailUserResponseData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectMemberResponseData;
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
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
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
            'updated_by' => Auth::id()
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS, ProjectResponseData::from($project));
    }

    public function delete(Project $project): ApiResponseData
    {
        $project->delete();
        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function getPM(Project $project): ApiResponseData
    {
        $member = $project->projectMembers()
            ->whereHas('roles', fn ($q) => $q->where('roles.code', 'PM'))
            ->with('user')
            ->firstOrFail();

        return ApiResponse::from(
            ResponseCode::SUCCESS,
            DetailUserResponseData::from($member->user)
        );
    }

    public function getMembers(Project $project): ApiResponseData
    {
        $members_response_data = $project->projectMembers()
            ->with([
                'user',
                'roles:id,code,name',
            ])
            ->get()
            ->map(fn ($member) => ProjectMemberResponseData::from($member));

        return ApiResponse::from(
            ResponseCode::SUCCESS,
            $members_response_data
        );
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
            $pmRoleId = Role::where('code', 'PM')->value('id');

            foreach ($data->members as $memberData) {

                // Ensure project membership exists
                $member = ProjectMember::query()->firstOrCreate([
                    'project_id' => $project->id,
                    'user_id'    => $memberData->user_id,
                ]);

                // Does this member currently have PM?
                $hasPm = $member->roles()
                    ->where('roles.id', $pmRoleId)
                    ->exists();

                // Resolve role IDs by code
                $roleIds = Role::query()
                    ->whereIn('code', $memberData->roles)
                    ->pluck('id')
                    ->all();

                // Assign roles to this project member
                $member->roles()->sync($roleIds);

                // Re-attach PM if it existed
                if ($hasPm) {
                    $member->roles()->syncWithoutDetaching([$pmRoleId]);
                }
            }

            // Return fresh state
            $members = $project->projectMembers()
                ->with(['user', 'roles:id,code,name'])
                ->get();

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                $members->map(fn ($m) => ProjectMemberResponseData::from($m))
            );
        });
    }

    public function getSettings(Project $project): ApiResponseData
    {
        $wiki = $project->wiki()->first();
        $document = $project->document()->first();

        return ApiResponse::from(ResponseCode::SUCCESS, [
            'project_id' => $project->id,

            'wiki' => $wiki ? [
                'content' => $wiki->content()->first()->content,
            ] : null,

            'document' => $document ? [
                'title'       => $document->title,
                'description' => $document->description,
                'created_by'  => $document->created_by,
            ] : null,
        ]);
    }

    public function updateSettings(Project $project, ProjectSettingsData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {
            // Wiki
            $wiki = $project->wiki()->first();
            if ($data->wiki_content !== null) {
                $wiki = $wiki ?? $project->wiki()->create();

                $wiki->content()->updateOrCreate(
                    [],
                    ['content' => $data->wiki_content]
                );
            }

            // Document
            $document = $project->document()->first();
            if ($data->document !== null) {
                if (!$document) {
                    $document = $project->document()->create([
                        'title'       => $data->document->title,
                        'description' => $data->document->description,
                        'created_by'  => Auth::id(),
                    ]);
                } else {
                    $document->update([
                        'title'       => $data->document->title,
                        'description' => $data->document->description,
                    ]);
                }
            }

            return ApiResponse::from(ResponseCode::SUCCESS, [
                'project_id' => $project->id,

                'wiki' => $wiki ? [
                    'content' => $wiki->content()->first()->content,
                ] : null,

                'document' => $document ? [
                    'title'       => $document->title,
                    'description' => $document->description,
                    'created_by'  => $document->created_by,
                ] : null,
            ]);
        });
    }

    public function getSchedule(Project $project): ApiResponseData
    {
        $version = $project->version()->first();
        return ApiResponse::from(
            ResponseCode::SUCCESS,
            [
                'project_id'    => $project->id,
                'version'       => $version ? ProjectScheduleData::from($version) : null,
            ]
        );
    }

    public function updateSchedule(Project $project, ProjectScheduleData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {
            $version = $project->version()->updateOrCreate(
                [
                    'name'          => $data->name,
                    'description'   => $data->description,
                    'start_date'    => $data->start_date,
                    'end_date'      => $data->end_date,
                ]
            );

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                [
                    'project_id'    => $project->id,
                    'version'       => ProjectScheduleData::from($version),
                ]
            );
        });
    }
}