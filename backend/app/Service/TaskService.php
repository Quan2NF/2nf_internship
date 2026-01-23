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
            ]);

        /** --------------------
         * Basic filters
         * -------------------- */
        $query->where('project_id', $project->id);

        $query->when($data->status_id, fn ($q) =>
            $q->where('status_id', $data->status_id)
        );

        $query->when($data->type_id, fn ($q) =>
            $q->where('type_id', $data->type_id)
        );

        $query->when($data->priority_id, fn ($q) =>
            $q->where('priority_id', $data->priority_id)
        );

        $query->when($data->assigned_to, fn ($q) =>
            $q->where('assigned_to', $data->assigned_to)
        );

        $query->when($data->created_by, fn ($q) =>
            $q->where('created_by', $data->created_by)
        );

        $query->when(!is_null($data->is_private), fn ($q) =>
            $q->where('is_private', $data->is_private)
        );

        /** --------------------
         * Keyword search
         * -------------------- */
        $query->when($data->keyword, function ($q) use ($data) {
            $q->where(function ($sub) use ($data) {
                $sub->where('subject', 'like', "%{$data->keyword}%")
                    ->orWhere('description', 'like', "%{$data->keyword}%");
            });
        });

        /** --------------------
         * Date range filters
         * -------------------- */
        $query->when($data->start_date_from, fn ($q) =>
            $q->whereDate('start_date', '>=', $data->start_date_from)
        );

        $query->when($data->start_date_to, fn ($q) =>
            $q->whereDate('start_date', '<=', $data->start_date_to)
        );

        $query->when($data->due_date_from, fn ($q) =>
            $q->whereDate('due_date', '>=', $data->due_date_from)
        );

        $query->when($data->due_date_to, fn ($q) =>
            $q->whereDate('due_date', '<=', $data->due_date_to)
        );

        /** --------------------
         * Sorting (whitelisted)
         * -------------------- */
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

        $query->orderBy($sortBy, $sortDir);

        /** --------------------
         * Pagination
         * -------------------- */
        $tasks = $query->paginate(
            perPage: $data->per_page,
            page: $data->page
        );

        return ApiResponse::from(ResponseCode::SUCCESS, $tasks);
    }

    public function create(Project $project, TaskData $data): ApiResponseData
    {
        return DB::transaction(function () use ($project, $data) {

            $task = $project->tasks()->create([
                'parent_id'        => $data->parent_id,

                'subject'          => $data->subject,
                'description'      => $data->description,

                'status_id'        => $data->status_id,
                'type_id'          => $data->type_id,
                'priority_id'      => $data->priority_id,
                'assigned_to'      => $data->assigned_to,

                'start_date'       => $data->start_date,
                'due_date'         => $data->due_date,

                'estimated_hours'  => $data->estimated_hours,
                'actual_hours'     => $data->actual_hours,

                'progress_rate'    => $data->progress_rate,
                'is_private'       => $data->is_private,

                'closed_at'        => $data->closed_at,

                'created_by'       => Auth::id(),
            ]);

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                data: TaskData::from($task)
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

            // 1️⃣ Capture original values (only fields we care about)
            $original = $task->getOriginal();

            // 2️⃣ Prepare update payload (PATCH-safe)
            $payload = array_filter(
                $data->toArray(),
                static fn ($value) => $value !== null
            );

            // Nothing to update → early return
            if (empty($payload)) {
                return ApiResponse::from(
                    ResponseCode::SUCCESS,
                    data: TaskData::from($task)
                );
            }

            // Apply update
            $task->fill($payload);
            $task->save();

            // Log changes
            foreach ($task->getChanges() as $field => $newValue) {
                if ($field === 'updated_at') {
                    continue;
                }

                TaskLog::create([
                    'task_id'   => $task->id,
                    'user_id'   => Auth::id(),
                    'field'     => $field,
                    'old_value' => $this->normalize($original[$field] ?? null),
                    'new_value' => $this->normalize($newValue),
                ]);
            }

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                data: TaskData::from($task)
            );
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