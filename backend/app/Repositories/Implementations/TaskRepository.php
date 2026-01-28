<?php

namespace App\Repositories\Implementations;

use App\Models\Task;
use App\Repositories\Interfaces\ITaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository implements ITaskRepository
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function paginateByFilter(array $filter, int $perPage = 15): LengthAwarePaginator
    {
        $q = Task::query();

        if (!empty($filter['project_id'])) {
            $q->where('project_id', (int) $filter['project_id']);
        }

        if (!empty($filter['keyword'])) {
            $kw = trim((string) $filter['keyword']);
            $q->where(function ($sub) use ($kw) {
                $sub->where('subject', 'like', "%{$kw}%")
                    ->orWhere('description', 'like', "%{$kw}%");
            });
        }

        foreach (['status_id', 'type_id', 'priority_id', 'assigned_to'] as $k) {
            if (!empty($filter[$k])) {
                $q->where($k, (int) $filter[$k]);
            }
        }

        if (isset($filter['is_private']) && $filter['is_private'] !== '') {
            $q->where('is_private', (int) $filter['is_private']);
        }

        return $q->orderByDesc('id')->paginate($perPage);
    }

    public function findById(int $id): ?Task
    {
        return Task::query()->find($id);
    }

    public function createTask(array $data): Task
    {
        return Task::query()->create($data);
    }

    public function updateTask(int $id, array $data): Task
    {
        $task = $this->findById($id);
        if (!$task) {
            throw new \RuntimeException('Task not found');
        }

        $task->fill($data);
        $task->save();

        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = $this->findById($id);
        if (!$task) {
            throw new \RuntimeException('Task not found');
        }
        $task->delete(); // soft delete
    }

    public function addComment(int $taskId, int $userId, string $content): void
    {
        DB::table('task_comments')->insert([
            'task_id' => $taskId,
            'user_id' => $userId,
            'content' => $content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function addLog(int $taskId, int $userId, string $field, ?string $oldValue, ?string $newValue): void
    {
        DB::table('task_logs')->insert([
            'task_id' => $taskId,
            'user_id' => $userId,
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function getLogs(int $taskId): array
    {
        $rows = DB::table('task_logs as tl')
            ->leftJoin('users as u', 'u.id', '=', 'tl.user_id')
            ->select([
                'tl.id',
                'tl.task_id',
                'tl.user_id',
                'u.name as user_name',
                'tl.action',
                'tl.old_value',
                'tl.new_value',
                'tl.created_at',
            ])
            ->where('tl.task_id', $taskId)
            ->orderByDesc('tl.id')
            ->get()
            ->toArray();

        return $rows;
    }
}
