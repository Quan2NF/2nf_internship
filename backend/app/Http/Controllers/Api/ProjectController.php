<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Models\Project;
use App\Services\Interfaces\IProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IProjectService $projectService
    ) {}

    /**
     * GET /api/projects/my
     */
    public function myProjects(Request $request)
    {
        $userId = (int) $request->user()->id();
        $projects = $this->projectService->getMyProjects($userId);

        return $this->success(
            message: 'GET_MY_PROJECTS_SUCCESS',
            data: $projects
        );
    }

    /**
     * POST /api/projects
     */
    public function store(ProjectCreateRequest $request)
    {
        $this->authorize('create', Project::class);

        $userId = (int) $request->user()->id; 

        $project = $this->projectService->create($request->validated(), $userId);

        return $this->success(
        message: 'CREATE_PROJECT_SUCCESS',
        data: \App\Data\Project\ProjectData::fromModel($project)
    );
    }

    /**
     * DELETE /api/projects/{id}
     */
    public function destroy(int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('delete', $project);

        $this->projectService->delete($id);

        return $this->success(
            message: 'DELETE_PROJECT_SUCCESS'
        );
    }
}
