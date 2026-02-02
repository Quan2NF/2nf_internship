<?php

namespace App\Service;

use App\Models\Task;

use App\Models\Project;
use App\Models\TaskLog;
use App\Data\Task\TaskData;
use App\Enums\ResponseCode;
use App\Models\TaskComment;
use App\Data\Task\CommentData;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Data\Task\TaskListFilterData;
use App\Data\Response\ApiResponseData;
use App\Contracts\Service\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{
    public function getFilteredList(Project $project, TaskListFilterData $data): ApiResponseData
    {
        $query = Task::query()
            ->with([
                'project',
                'assignee',
                'creator',
                'status',
                'type',
                'priority',
                'parent',
            ])
            ->where('project_id', $project->id)

            ->when($data->status_id !== null, fn ($q) => $q->where('status_id', $data->status_id))
            ->when($data->type_id !== null, fn ($q) => $q->where('type_id', $data->type_id))
            ->when($data->priority_id !== null, fn ($q) => $q->where('priority_id', $data->priority_id))
            ->when($data->assigned_to !== null, fn ($q) => $q->where('assigned_to', $data->assigned_to))
            ->when($data->created_by !== null, fn ($q) => $q->where('created_by', $data->created_by))
            ->when($data->is_private !== null, fn ($q) => $q->where('is_private', $data->is_private))

            ->when(
                $data->keyword !== null && $data->keyword !== '',
                fn ($q) => $q->where(fn ($sub) =>
                    $sub->where('subject', 'like', "%{$data->keyword}%")
                        ->orWhere('description', 'like', "%{$data->keyword}%")
                )
            )

            ->when($data->start_date_from !== null, fn ($q) => $q->where('start_date', '>=', $data->start_date_from))
            ->when($data->start_date_to !== null, fn ($q) => $q->where('start_date', '<=', $data->start_date_to))
            ->when($data->due_date_from !== null, fn ($q) => $q->where('due_date', '>=', $data->due_date_from))
            ->when($data->due_date_to !== null, fn ($q) => $q->where('due_date', '<=', $data->due_date_to));

        $allowedSorts = [
            'id',
            'start_date',
            'due_date',
            'priority_id',
            'status_id',
            'created_at',
        ];

        $sortBy = in_array($data->sort_by, $allowedSorts, true)
            ? $data->sort_by
            : 'id';

        $sortDir = strtolower($data->sort_dir) === 'asc' ? 'asc' : 'desc';

        $tasks = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(
                perPage: $data->per_page,
                page: $data->page
            );

        return ApiResponse::from(ResponseCode::SUCCESS, $tasks);
    }

    public function create(Project $project, TaskData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {

            $task = $project->tasks()->create([
                ...$data->toArray(),
                'created_by' => Auth::id(),
            ]);

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                ['id' => $task->id]
            );
        });
    }

    public function view(Task $task): ApiResponseData
    {
        return ApiResponse::from(
            ResponseCode::SUCCESS,
            data: TaskData::from($task->load([
                'project',
                'assignee',
                'creator',
                'status',
                'type',
                'priority',
                'parent',
            ]))
        );
    }

    public function update(Task $task, TaskData $data): ApiResponseData
    {
        return DB::transaction(function () use ($task, $data) {

            $original = $task->getOriginal();

            $payload = collect($data->all())
                ->filter(fn ($v) => $v !== null)
                ->toArray();

            if (empty($payload)) {
                return ApiResponse::from(ResponseCode::SUCCESS, TaskData::from($task));
            }

            $task->fill($payload);

            if (! $task->isDirty()) {
                return ApiResponse::from(ResponseCode::SUCCESS, TaskData::from($task));
            }

            $task->save();

            $now  = now();
            $logs = [];

            foreach ($task->getChanges() as $field => $newValue) {
                if ($field === 'updated_at') continue;

                $logs[] = [
                    'task_id'    => $task->id,
                    'user_id'    => Auth::id(),
                    'field'      => $field,
                    'old_value'  => $this->normalize($original[$field] ?? null),
                    'new_value'  => $this->normalize($newValue),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($logs) {
                TaskLog::insert($logs);
            }

            return ApiResponse::from(ResponseCode::SUCCESS, TaskData::from($task));
        });
    }

    private function normalize(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }

    public function delete(Task $task): ApiResponseData
    {
        $task->delete();
        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function getComments(Task $task): ApiResponseData
    {
        $comments = $task->comments()->get();

        return ApiResponse::from(ResponseCode::SUCCESS, $comments);
    }
    
    public function postComment(Task $task, CommentData $data): ApiResponseData
    {
        return DB::transaction(function () use ($task, $data) {
            $comment = $task->comments()->create([
                'user_id' => Auth::id(),
                'content' => $data->content,
            ]);

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                data: [
                    'id'         => $comment->id,
                    'content'    => $comment->content,
                    'user_id'    => $comment->user_id,
                    'task_id'    => $comment->task_id,
                    'created_at'=> $comment->created_at,
                ]
            );
        });
    }
}