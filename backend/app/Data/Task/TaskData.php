<?php

namespace App\Data\Task;

use App\Data\Common\EntityData;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Data Transfer Object representing a Task entity.
 */
class TaskData extends EntityData
{
    /**
     * @param int $project_id
     * @param int|null $parent_id
     * @param string $subject
     * @param string|null $description
     * @param int $status_id
     * @param int $type_id
     * @param int $priority_id
     * @param int|null $assigned_to
     * @param int $created_by
     * @param \DateTime|null $start_date
     * @param \DateTime|null $due_date
     * @param float|null $estimated_hours
     * @param float|null $actual_hours
     * @param int $progress_rate
     * @param bool $is_private
     * @param \DateTime|null $closed_at
     */
    public function __construct(
        public int $project_id,
        public ?int $parent_id,

        public string $subject,
        public ?string $description,

        public int $status_id,
        public int $type_id,
        public int $priority_id,
        public ?int $assigned_to,
        public int $created_by,

        #[WithCast('date')]
        public ?\DateTime $start_date,

        #[WithCast('date')]
        public ?\DateTime $due_date,

        public ?float $estimated_hours,
        public ?float $actual_hours,

        public int $progress_rate = 0,
        public bool $is_private = false,

        #[WithCast('datetime')]
        public ?\DateTime $closed_at,
    ) {}
}
