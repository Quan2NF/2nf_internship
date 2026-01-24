<?php

namespace App\Http\Controllers;

use App\Data\CreateProjectData;
use App\Data\ListProjectsData;
use App\Data\UpdateProjectData;
use App\Exceptions\BusinessException;
use App\Http\Requests\AssignMembersRequest;
use App\Http\Requests\AssignPmRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ListProjectsRequest;
use App\Http\Requests\ScheduleRequest;
use App\Http\Requests\SettingRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectServiceInterface $projects) {}

    public function index(ListProjectsRequest $request)
    {
        $filter = ListProjectsData::fromRequest($request);
        try {
            $result = $this->projects->listProjects($filter);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function store(CreateProjectRequest $request)
    {
        $user = Auth::user();
        if (! ($user->isAdmin() || $user->isPMO())) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $data = CreateProjectData::fromRequest($request);
        try {
            $result = $this->projects->createProject($data);
            return response()->json(['data' => $result], 201);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $user = Auth::user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $data = UpdateProjectData::fromRequest($request);
        try {
            $result = $this->projects->updateProject($project->id, $data);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function destroy(Project $project)
    {
        $user = Auth::user();
        if (! ($user->isAdmin() || $user->isPMO())) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        try {
            $this->projects->deleteProject($project->id);
            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function assignPm(AssignPmRequest $request, Project $project)
    {
        $user = Auth::user();
        if (! ($user->isAdmin() || $user->isPMO())) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $pmId = $request->integer('pm_id');
        try {
            $this->projects->assignPm($project->id, $pmId);
            return response()->json(['message' => 'PM assigned'], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function assignMembers(AssignMembersRequest $request, Project $project)
    {
        $user = Auth::user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $members = $request->input('members', []);
        try {
            $this->projects->assignMembers($project->id, $members);
            return response()->json(['message' => 'Members assigned'], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function setSettings(SettingRequest $request, Project $project)
    {
        $user = Auth::user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $settings = $request->input('settings');
        try {
            $result = $this->projects->setSettings($project->id, $settings);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function updateSettings(SettingRequest $request, Project $project)
    {
        $user = Auth::user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $settings = $request->input('settings');
        try {
            $result = $this->projects->updateSettings($project->id, $settings);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function getSchedule(Project $project)
    {
        $user = auth()->user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        try {
            $result = $this->projects->getSchedule($project->id);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function updateSchedule(ScheduleRequest $request, Project $project)
    {
        $user = auth()->user();
        $allowed = $user->isAdmin() || $user->isPMO() || $user->isPmOfProject($project->id);
        if (! $allowed) {
            return response()->json(['statusCode' => 403, 'message' => 'Forbidden'], 403);
        }
        $schedule = $request->input('schedule');
        try {
            $result = $this->projects->updateSchedule($project->id, $schedule);
            return response()->json(['data' => $result], 200);
        } catch (BusinessException $e) {
            return response()->json(['statusCode' => $e->getStatusCode(), 'message' => $e->getMessage()], $e->getStatusCode());
        }
    }
}
