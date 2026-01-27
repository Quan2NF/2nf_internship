<?php
namespace App\Repositories;

use App\Models\Task;
use App\Data\ListTasksData;
use App\Data\CreateTaskData;
use App\Data\UpdateTaskData;
use App\Data\CreateTaskCommentData;
use Illuminate\Support\Facades\DB;
class TaskRepository implements TaskRepositoryInterface
{
    public function paginateTasks(ListTasksData $filter)
    {
        $query = Task::query();
        if ($filter->search) {
            $q = trim($filter->search);
            $query->where(function ($q2) use ($q) {
                $q2->where('subject', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($filter->status_id) {
            $query->where('status_id', $filter->status_id);
        }
        if ($filter->project_id) {
            $query->where('project_id', $filter->project_id);
        }
        $query->orderBy('id', 'desc');
        return $query->paginate($filter->per_page ?? 20);
    }

    public function createTask(CreateTaskData $data)
    {
        $payload = [
            'project_id' => $data->project_id,
            'parent_id' => $data->parent_id,
            'subject' => $data->subject,
            'description' => $data->description,
            'status_id' => $data->status_id,
            'type_id' => $data->type_id,
            'priority_id' => $data->priority_id,
            'assigned_to' => $data->assigned_to,
            'created_by' => $data->created_by,
            'start_date' => $data->start_date,
            'due_date' => $data->due_date,
            'estimated_hours' => $data->estimated_hours,
            'actual_hours' => $data->actual_hours,
            'progress_rate' => $data->progress_rate,
            'is_private' => $data->is_private,
            'closed_at' => $data->closed_at,
        ];
        \Log::debug('TaskRepository create payload', $payload);
        $task = Task::create($payload);

        // insert task log
        try {
            \Log::debug('TaskRepository will insert task_log (create)', ['task_id' => $task->id, 'db' => DB::connection()->getDatabaseName(), 'user_id' => $payload['created_by'] ?? auth()->id() ?? null]);
            DB::table('task_logs')->insert([
                'task_id' => $task->id,
                'user_id' => $payload['created_by'] ?? auth()->id() ?? null,
                'field' => 'create',
                'old_value' => null,
                'new_value' => 'created',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to insert task_log on create', ['error' => $e->getMessage(), 'task_id' => $task->id, 'payload' => $payload]);
        }

        return $task;
    }

    public function updateTask(int $id, UpdateTaskData $data)
    {
        $task = Task::find($id);
        if (! $task) return null;
        $updates = $data->toArray();
        $filtered = array_filter($updates, fn($v) => $v !== null);

        // capture original values for changed fields
        $original = $task->getOriginal();

        $task->fill($filtered);
        $task->save();

        // record per-field changes
        $changes = $task->getChanges();
        if (! empty($changes)) {
            foreach ($changes as $field => $newVal) {
                try {
                    \Log::debug('TaskRepository will insert task_log (update)', ['task_id' => $task->id, 'field' => $field, 'db' => DB::connection()->getDatabaseName(), 'user_id' => auth()->id() ?? null]);
                    DB::table('task_logs')->insert([
                        'task_id' => $task->id,
                        'user_id' => auth()->id() ?? null,
                        'field' => $field,
                        'old_value' => isset($original[$field]) ? (string)$original[$field] : null,
                        'new_value' => is_null($newVal) ? null : (string)$newVal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    \Log::error('Failed to insert task_log on update', ['error' => $e->getMessage(), 'task_id' => $task->id, 'field' => $field]);
                }
            }
        }

        return $task;
    }

    public function deleteTask(int $id)
    {
        $task = Task::find($id);
        if (! $task) return false;
        \Log::debug('TaskRepository delete called', ['id' => $id, 'user_id' => auth()->id() ?? null]);

        try {
            \Log::debug('TaskRepository will insert task_log (delete)', ['task_id' => $task->id, 'db' => DB::connection()->getDatabaseName(), 'user_id' => auth()->id() ?? null]);
            DB::table('task_logs')->insert([
                'task_id' => $task->id,
                'user_id' => auth()->id() ?? null,
                'field' => 'delete',
                'old_value' => json_encode($task->toArray()),
                'new_value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to insert task_log on delete', ['error' => $e->getMessage(), 'task_id' => $task->id]);
        }

        $task->delete();
        return true;
    }

    public function addComment(CreateTaskCommentData $data)
    {
        return DB::table('task_comments')->insertGetId([
            'task_id' => $data->task_id,
            'user_id' => $data->user_id,
            'content' => $data->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function getLogs(int $taskId)
    {
        $rows = DB::table('task_logs')
            ->leftJoin('users', 'users.id', '=', 'task_logs.user_id')
            ->where('task_logs.task_id', $taskId)
            ->orderBy('task_logs.created_at', 'desc')
            ->get([
                'task_logs.*',
                'users.name as user_name',
                'users.email as user_email',
            ]);

        return $rows->map(function ($r) {
            return [
                'id' => $r->id,
                'task_id' => $r->task_id,
                'user' => [
                    'id' => $r->user_id,
                    'name' => $r->user_name ?? null,
                    'email' => $r->user_email ?? null,
                ],
                'field' => $r->field,
                'old_value' => $r->old_value,
                'new_value' => $r->new_value,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
            ];
        })->values();
    }
}
