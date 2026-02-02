<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Data\Project\AssignPMData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectScheduleData;
use App\Data\Project\ProjectSettingsData;
use App\Data\Project\ProjectListFilterData;
use App\Http\Requests\Project\GetPMRequest;
use App\Http\Requests\Project\AssignPMRequest;
use App\Data\Project\AssignMembersToProjectData;
use App\Http\Requests\Project\GetMembersRequest;
use App\Http\Requests\Project\GetScheduleRequest;
use App\Http\Requests\Project\GetSettingsRequest;
use App\Http\Requests\Project\ViewProjectRequest;
use App\Contracts\Service\ProjectServiceInterface;
use App\Http\Requests\Project\AssignMembersRequest;
use App\Http\Requests\Project\CreateProjectRequest;
use App\Http\Requests\Project\DeleteProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\UpdateScheduleRequest;
use App\Http\Requests\Project\UpdateSettingsRequest;
use App\Http\Requests\Project\GetFilteredProjectListRequest;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectServiceInterface $projectService
    ){}

    public function getFilteredList(GetFilteredProjectListRequest $request): ApiResponseData
    {
        return $this->projectService->getFilteredList(Auth::user(), ProjectListFilterData::from($request->validated()));
    }

    public function create(CreateProjectRequest $request): ApiResponseData
    {
        return $this->projectService->create(ProjectRequestData::from($request->validated()));
    }

    public function view(Project $project, ViewProjectRequest $request): ApiResponseData
    {
        return $this->projectService->view($project);
    }

    public function update(Project $project, UpdateProjectRequest $request): ApiResponseData
    {
        return $this->projectService->update($project, ProjectRequestData::from($request->validated()));
    }

    public function delete(Project $project, DeleteProjectRequest $request): ApiResponseData
    {
        return $this->projectService->delete($project);
    }

    public function getPM(Project $project, GetPMRequest $request): ApiResponseData
    {
        return $this->projectService->getPM($project);
    }

    public function assignPM(Project $project, AssignPMRequest $request): ApiResponseData
    {
        return $this->projectService->assignPM($project, AssignPMData::from($request->validated()));
    }

    public function getMembers(Project $project, GetMembersRequest $request): ApiResponseData
    {
        return $this->projectService->getMembers($project);
    }

    public function assignMembers(Project $project, AssignMembersRequest $request): ApiResponseData
    {
        return $this->projectService->assignMembers($project, AssignMembersToProjectData::from($request->validated()));
    }

    public function getSettings(Project $project, GetSettingsRequest $request): ApiResponseData
    {
        return $this->projectService->getSettings($project);
    }

    public function updateSettings(Project $project, UpdateSettingsRequest $request): ApiResponseData
    {
        return $this->projectService->updateSettings($project, ProjectSettingsData::from($request->validated()));
    }

    public function getSchedule(Project $project, GetScheduleRequest $request): ApiResponseData
    {
        return $this->projectService->getSchedule($project);
    }

    public function updateSchedule(Project $project, UpdateScheduleRequest $request): ApiResponseData
    {
        return $this->projectService->updateSchedule($project, ProjectScheduleData::from($request->validated()));
    }
}
