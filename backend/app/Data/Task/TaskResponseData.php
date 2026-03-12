<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;
use App\Models\Task;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class TaskResponseData extends Data
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
        public int $created_by,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $start_date,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $due_date,

        public ?float $estimated_hours,
        public ?float $actual_hours,

        public int $progress_rate,
        public bool $is_private,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?\DateTime $closed_at,
    ) {}

    public static function fromTask(Task $task): self
    {
        return new self(
            id: $task->id,
            project_id: $task->project_id,
            parent_id: $task->parent_id,
            subject: $task->subject,
            description: $task->description,
            status_id: $task->status_id,
            type_id: $task->type_id,
            priority_id: $task->priority_id,
            assigned_to: $task->assigned_to,
            created_by: $task->created_by,
            start_date: $task->start_date,
            due_date: $task->due_date,
            estimated_hours: $task->estimated_hours,
            actual_hours: $task->actual_hours,
            progress_rate: $task->progress_rate,
            is_private: $task->is_private,
            closed_at: $task->closed_at,
        );
    }
}