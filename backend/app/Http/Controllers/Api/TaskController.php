<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Models\Task;
use App\Services\Interfaces\ITaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ITaskService $taskService
    ) {}

    /**
     * GET /api/tasks?project_id=1
     */
    public function index(Request $request)
    {
        $projectId = (int) $request->query('project_id');
        if ($projectId <= 0) {
            abort(422, 'project_id is required');
        }

        $tasks = $this->taskService->getByProject($projectId);

        return $this->success(
            message: 'GET_TASKS_SUCCESS',
            data: $tasks
        );
    }

    /**
     * POST /api/tasks
     */
    public function store(TaskCreateRequest $request)
    {
        $data = $request->validated();

        $projectId = (int) $data['project_id'];
        $this->authorize('create', [Task::class, $projectId]);

        $userId = (int) $request->user()->id;
        $data['created_by'] = $userId;

        $task = $this->taskService->create($data);

        return $this->success(
        message: 'CREATE_TASK_SUCCESS',
        data: \App\Data\Task\TaskData::fromModel($task)
    );

    }

    /**
     * DELETE /api/tasks/{id}
     */
    public function destroy(int $id)
    {
        $task = Task::query()->findOrFail($id);
        $this->authorize('delete', $task);

        $this->taskService->delete($id);

        return $this->success(
            message: 'DELETE_TASK_SUCCESS'
        );
    }
}
