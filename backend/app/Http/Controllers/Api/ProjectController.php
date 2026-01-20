<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectListFilterData;
use App\Http\Requests\Project\ViewProjectRequest;
use App\Contracts\Service\ProjectServiceInterface;
use App\Http\Requests\Project\CreateProjectRequest;
use App\Http\Requests\Project\DeleteProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\GetFilteredProjectListRequest;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectServiceInterface $projectService
    ){}

    public function getFilteredList(GetFilteredProjectListRequest $request): ApiResponseData
    {
        return $this->projectService->getFilteredList(ProjectListFilterData::from($request->validated()));
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
}
