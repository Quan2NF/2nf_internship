<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCommentRequest;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Services\Interfaces\ITaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly ITaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $result = $this->taskService->list(
            filter: $request->only([
                'project_id', 'keyword', 'status_id', 'type_id',
                'priority_id', 'assigned_to', 'is_private',
            ]),
            perPage: (int) $request->get('per_page', 15),
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'GET_TASKS_SUCCESS', data: $result);
    }

    public function store(TaskCreateRequest $request)
    {
        $task = $this->taskService->create(
            data: $request->validated(),
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'CREATE_TASK_SUCCESS', data: $task);
    }

    public function update(TaskUpdateRequest $request, int $id)
    {
        $task = $this->taskService->update(
            id: $id,
            data: $request->validated(),
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'UPDATE_TASK_SUCCESS', data: $task);
    }

    public function destroy(Request $request, int $id)
    {
        $this->taskService->delete(
            id: $id,
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'DELETE_TASK_SUCCESS', data: null);
    }

    public function comment(TaskCommentRequest $request, int $id)
    {
        $this->taskService->comment(
            taskId: $id,
            content: (string) $request->validated()['content'],
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'COMMENT_TASK_SUCCESS', data: null);
    }

        public function logs(Request $request, int $id)
    {
        $result = $this->taskService->logs(
            taskId: $id,
            userId: (int) $request->user()->id
        );

        return $this->success(message: 'GET_TASK_LOGS_SUCCESS', data: $result);
    }
}
