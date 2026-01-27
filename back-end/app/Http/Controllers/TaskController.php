<?php
namespace App\Http\Controllers;

use App\Http\Requests\ListTasksRequest;
use App\Data\ListTasksData;
use App\Services\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateTaskRequest;
use App\Data\CreateTaskData;
use App\Http\Requests\UpdateTaskRequest;
use App\Data\UpdateTaskData;
use App\Http\Requests\CreateTaskCommentRequest;
use App\Data\CreateTaskCommentData;

class TaskController extends Controller
{
    public function __construct(private readonly TaskServiceInterface $tasks) {}
    public function index(ListTasksRequest $request): JsonResponse
    {
        $filter = ListTasksData::fromRequest($request);
        $result = $this->tasks->listTasks($filter);
        return response()->json(['data' => $result], 200);
    }
    public function store(CreateTaskRequest $request)
    {
        \Log::debug('CreateTask payload', $request->all());
        \Log::debug('CreateTask authenticated user', [$request->user()?->id ?? null]);
        $data = CreateTaskData::fromRequest($request);
        $task = $this->tasks->createTask($data);
        return response()->json(['data' => $task], 201);
    }
    public function update(UpdateTaskRequest $request, $id)
    {
        $data = UpdateTaskData::fromRequest($request);
        $task = $this->tasks->updateTask($id, $data);
        if (! $task) return response()->json(['message' => 'Task not found'], 404);
        return response()->json(['data' => $task], 200);
    }

    public function destroy($id)
    {
        $ok = $this->tasks->deleteTask($id);
        if (! $ok) return response()->json(['message' => 'Task not found'], 404);
        return response()->json(['message' => 'Task deleted'], 200);
    }
    public function comment(CreateTaskCommentRequest $request, $id)
    {
        $data = CreateTaskCommentData::fromRequest($request, $id);
        $commentId = $this->tasks->addComment($data);
        return response()->json(['comment_id' => $commentId], 201);
    }
    public function logs($id)
    {
        $logs = $this->tasks->getLogs($id);
        return response()->json(['data' => $logs], 200);
    }
}
