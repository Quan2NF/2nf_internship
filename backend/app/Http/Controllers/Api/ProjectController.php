<?php

namespace App\Http\Controllers\Api;

use App\Data\Project\ProjectData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Models\Project;
use App\Services\Interfaces\IProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly IProjectService $projectService
    ) {}

    // API16 + API17
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $actorId = (int) $request->user()->id;

        $filters = [
            'keyword'   => $request->query('keyword', ''),
            'status'    => $request->query('status', ''),
            'is_active' => $request->query('is_active', ''),
        ];

        $perPage = (int) $request->query('per_page', 15);

        $page = $this->projectService->listProjects($actorId, $filters, $perPage);

        // map data
        $items = collect($page->items())->map(fn ($p) => ProjectData::fromModel($p));

        return $this->success(message: 'GET_PROJECTS_SUCCESS', data: [
            'projects' => $items,
            'total' => $page->total(),
            'per_page' => $page->perPage(),
            'current_page' => $page->currentPage(),
            'last_page' => $page->lastPage(),
        ]);
    }

    // API18
    public function store(ProjectCreateRequest $request)
    {
        $this->authorize('create', Project::class);

        $actorId = (int) $request->user()->id;
        $project = $this->projectService->create($request->validated(), $actorId);

        return $this->success(message: 'CREATE_PROJECT_SUCCESS', data: ProjectData::fromModel($project));
    }

    // API19
    public function update(ProjectUpdateRequest $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('update', $project);

        $actorId = (int) $request->user()->id;

        $ok = $this->projectService->update($id, $request->validated(), $actorId);

        return $this->success(message: 'UPDATE_PROJECT_SUCCESS', data: [
            'updated' => $ok,
        ]);
    }

    // API20
    public function destroy(int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('delete', $project);

        $this->projectService->delete($id);

        return $this->success(message: 'DELETE_PROJECT_SUCCESS', data: [
            'deleted' => true,
        ]);
    }

    // API21
    public function assignPm(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('update', $project);

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $actorId = (int) $request->user()->id;
        $ok = $this->projectService->assignPm($id, (int) $data['user_id'], $actorId);

        return $this->success(message: 'ASSIGN_PM_SUCCESS', data: ['updated' => $ok]);
    }

    // API22
    public function assignMembers(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('update', $project);

        $data = $request->validate([
            'members' => ['required', 'array', 'min:1'],
            'members.*.user_id' => ['required', 'integer', 'exists:users,id'],
            'members.*.role_codes' => ['nullable', 'array'],
            'members.*.role_codes.*' => ['string', 'exists:roles,code'],
        ]);

        $actorId = (int) $request->user()->id;
        $ok = $this->projectService->assignMembers($id, $data['members'], $actorId);

        return $this->success(message: 'ASSIGN_MEMBERS_SUCCESS', data: ['updated' => $ok]);
    }

    // API23
    public function getSetting(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('view', $project);

        $data = $this->projectService->getSetting($id);

        return $this->success(message: 'GET_PROJECT_SETTING_SUCCESS', data: $data);
    }

    // API24
    public function updateSetting(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('update', $project);

        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $actorId = (int) $request->user()->id;
        $ok = $this->projectService->updateSetting($id, (string) $data['content'], $actorId);

        return $this->success(message: 'UPDATE_PROJECT_SETTING_SUCCESS', data: ['updated' => $ok]);
    }

    // API25
    public function getSchedule(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('view', $project);

        $data = $this->projectService->getSchedule($id);

        return $this->success(message: 'GET_PROJECT_SCHEDULE_SUCCESS', data: $data);
    }

    // API26
    public function updateSchedule(Request $request, int $id)
    {
        $project = Project::query()->findOrFail($id);
        $this->authorize('update', $project);

        $data = $request->validate([
            'versions' => ['required', 'array', 'min:1'],
            'versions.*.id' => ['nullable', 'integer'],
            'versions.*.name' => ['required', 'string'],
            'versions.*.description' => ['nullable', 'string'],
            'versions.*.start_date' => ['nullable', 'date'],
            'versions.*.end_date' => ['nullable', 'date'],
        ]);

        $actorId = (int) $request->user()->id;
        $ok = $this->projectService->updateSchedule($id, $data['versions'], $actorId);

        return $this->success(message: 'UPDATE_PROJECT_SCHEDULE_SUCCESS', data: ['updated' => $ok]);
    }   
}
