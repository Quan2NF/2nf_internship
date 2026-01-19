<?php

namespace App\Data\Task;

use App\Models\Task;
use Spatie\LaravelData\Data;

class TaskData extends Data
{
    public function __construct(
        public int $id,
        public int $project_id,
        public ?int $parent_id,
        public string $subject,
        public ?string $description,
        public int $status_id,
        public int $type_id,
        public int $priority_id,
        public ?int $assigned_to,
        public ?int $created_by,
        public ?string $start_date,
        public ?string $due_date,
        public ?float $estimated_hours,
        public ?float $actual_hours,
        public ?int $progress_rate,
        public ?int $is_private,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    /**
     * Create TaskData from Task model.
     */
    public static function fromModel(Task $task): self
    {
        return new self(
            id: (int) $task->id,
            project_id: (int) $task->project_id,
            parent_id: $task->parent_id ? (int) $task->parent_id : null,
            subject: (string) $task->subject,
            description: $task->description,
            status_id: (int) $task->status_id,
            type_id: (int) $task->type_id,
            priority_id: (int) $task->priority_id,
            assigned_to: $task->assigned_to ? (int) $task->assigned_to : null,
            created_by: $task->created_by ? (int) $task->created_by : null,
            start_date: optional($task->start_date)?->toDateString(),
            due_date: optional($task->due_date)?->toDateString(),
            estimated_hours: $task->estimated_hours !== null ? (float) $task->estimated_hours : null,
            actual_hours: $task->actual_hours !== null ? (float) $task->actual_hours : null,
            progress_rate: $task->progress_rate !== null ? (int) $task->progress_rate : null,
            is_private: $task->is_private !== null ? (int) $task->is_private : null,
            created_at: optional($task->created_at)?->toISOString(),
            updated_at: optional($task->updated_at)?->toISOString(),
        );
    }
}
