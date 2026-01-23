<?php

namespace App\Data\Task;

use App\Data\Common\EntityData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Data Transfer Object representing a Task entity.
 */
class TaskData extends EntityData
{
    public function __construct(
        public ?int $parent_id,

        public string $subject,
        public ?string $description,

        public int $status_id,
        public int $type_id,
        public int $priority_id,
        public ?int $assigned_to,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $start_date,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $due_date,

        public ?float $estimated_hours,
        public ?float $actual_hours,

        public int $progress_rate = 0,
        public bool $is_private = false,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?\DateTime $closed_at,
    ) {}
}
