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
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    use AuthorizesRequests;
    public function __construct(private readonly TaskServiceInterface $tasks) {}
    public function index(ListTasksRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);
        $filter = ListTasksData::fromRequest($request);
        $result = $this->tasks->listTasks($filter);
        return response()->json(['data' => $result], 200);
    }
    public function store(CreateTaskRequest $request)
    {
        // debug logs removed
        $data = CreateTaskData::fromRequest($request);
        $this->authorize('create', Task::class);
        $task = $this->tasks->createTask($data);
        return response()->json(['data' => $task], 201);
    }
    public function update(UpdateTaskRequest $request, $id)
    {
        $taskModel = Task::find($id);
        if (! $taskModel) return response()->json(['message' => 'Task not found'], 404);
        $this->authorize('update', $taskModel);
        $data = UpdateTaskData::fromRequest($request);
        $task = $this->tasks->updateTask($id, $data);
        if (! $task) return response()->json(['message' => 'Task not found'], 404);
        return response()->json(['data' => $task], 200);
    }

    public function destroy($id)
    {
        $taskModel = Task::find($id);
        if (! $taskModel) return response()->json(['message' => 'Task not found'], 404);
        $this->authorize('delete', $taskModel);
        $ok = $this->tasks->deleteTask($id);
        if (! $ok) return response()->json(['message' => 'Task not found'], 404);
        return response()->json(['message' => 'Task deleted'], 200);
    }
    public function comment(CreateTaskCommentRequest $request, $id)
    {
        $taskModel = Task::find($id);
        if (! $taskModel) return response()->json(['message' => 'Task not found'], 404);
        $this->authorize('view', $taskModel);
        $data = CreateTaskCommentData::fromRequest($request, $id);
        $commentId = $this->tasks->addComment($data);
        return response()->json(['comment_id' => $commentId], 201);
    }
    public function logs($id)
    {
        $taskModel = Task::find($id);
        if (! $taskModel) return response()->json(['message' => 'Task not found'], 404);
        $this->authorize('view', $taskModel);
        $logs = $this->tasks->getLogs($id);
        return response()->json(['data' => $logs], 200);
    }
}
