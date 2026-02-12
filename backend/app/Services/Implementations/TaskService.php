<?php

namespace App\Services\Implementations;

use App\Data\Task\TaskData;
use App\Exceptions\Domain\ForbiddenException;
use App\Exceptions\Domain\NotFoundException;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ITaskRepository;
use App\Services\Interfaces\ITaskService;
use Illuminate\Support\Facades\Gate;

class TaskService implements ITaskService
{
    public function __construct(
        private readonly ITaskRepository $taskRepository,
    ) {}
    public function list(array $filter, int $perPage, int $userId): array
    {
        // Nếu bạn có rule riêng để list theo project_members thì đặt ở đây.
        // Tạm thời: ai login cũng list theo filter.
        $tasks = $this->taskRepository->paginateByFilter($filter, $perPage);

        return [
            'items' => array_map(
                fn ($t) => TaskData::fromModel($t)->toArray(),
                $tasks->items()
            ),
            'total' => $tasks->total(),
            'page' => $tasks->currentPage(),
            'total_pages' => $tasks->lastPage(),
        ];
    }
    public function create(array $data, int $userId): array
    {
        $projectId = (int) $data['project_id'];
        // authorize ở service
        $user = User::query()->find($userId);
        if (!$user) {
            throw new ForbiddenException('Unauthenticated');
        }
        // Policy create: [Task::class, $projectId]
        if (Gate::forUser($user)->denies('create', [Task::class, $projectId])) {
            throw new ForbiddenException('Forbidden');
        }
        $data['created_by'] = $userId;
        $data['progress_rate'] = isset($data['progress_rate']) ? (int) $data['progress_rate'] : 0;
        $data['is_private'] = isset($data['is_private']) ? (int) $data['is_private'] : 0;
        $task = $this->taskRepository->createTask($data);
        // audit log (ví dụ)
        $this->taskRepository->addLog($task->id, $userId, 'created', null, '1');
        return TaskData::fromModel($task)->toArray();
    }
    public function update(int $id, array $data, int $userId): array
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            throw new NotFoundException('Task not found');
        }
        $user = User::query()->find($userId);
        if (!$user) {
            throw new ForbiddenException('Unauthenticated');
        }
        if (Gate::forUser($user)->denies('update', $task)) {
            throw new ForbiddenException('Forbidden');
        }
        // audit log ví dụ: status_id thay đổi
        if (array_key_exists('status_id', $data) && (int)$task->status_id !== (int)$data['status_id']) {
            $this->taskRepository->addLog(
                $task->id,
                $userId,
                'status_id',
                (string) $task->status_id,
                (string) $data['status_id']
            );
        }
        $updated = $this->taskRepository->updateTask($id, $data);
        return TaskData::fromModel($updated)->toArray();
    }
    public function delete(int $id, int $userId): void
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            throw new NotFoundException('Task not found');
        }

        $user = User::query()->find($userId);
        if (!$user) {
            throw new ForbiddenException('Unauthenticated');
        }

        if (Gate::forUser($user)->denies('delete', $task)) {
            throw new ForbiddenException('Forbidden');
        }

        $this->taskRepository->deleteTask($id);
        $this->taskRepository->addLog($id, $userId, 'deleted', null, '1');
    }

    public function comment(int $taskId, string $content, int $userId): void
    {
        $task = $this->taskRepository->findById($taskId);
        if (!$task) {
            throw new NotFoundException('Task not found');
        }

        $user = User::query()->find($userId);
        if (!$user) {
            throw new ForbiddenException('Unauthenticated');
        }

        if (Gate::forUser($user)->denies('comment', $task)) {
            throw new ForbiddenException('Forbidden');
        }

        $this->taskRepository->addComment($taskId, $userId, $content);
        $this->taskRepository->addLog($taskId, $userId, 'comment', null, '1');
    }

    public function logs(int $taskId, int $userId): array
    {
        $task = $this->taskRepository->findById($taskId);
        if (!$task) {
            throw new NotFoundException('Task not found');
        }

        $user = User::query()->find($userId);
        if (!$user) {
            throw new ForbiddenException('Unauthenticated');
        }

        if (Gate::forUser($user)->denies('view', $task)) {
            throw new ForbiddenException('Forbidden');
        }

        $logs = $this->taskRepository->getLogs($taskId);
        return [
            'logs' => $logs,
        ];
    }
}
