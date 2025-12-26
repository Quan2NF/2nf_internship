<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Filters used when listing tasks.
 */
class TaskListFilterData extends Data
{
    /**
     * @param int|null $project_id
     * @param int|null $status_id
     * @param int|null $type_id
     * @param int|null $priority_id
     * @param int|null $assigned_to
     * @param bool|null $is_private
     * @param string|null $keyword
     * @param \DateTime|null $start_from
     * @param \DateTime|null $due_to
     * @param int $page
     * @param int $per_page
     */
    public function __construct(
        public ?int $project_id = null,
        public ?int $status_id = null,
        public ?int $type_id = null,
        public ?int $priority_id = null,
        public ?int $assigned_to = null,
        public ?bool $is_private = null,
        public ?string $keyword = null,

        #[WithCast('date')]
        public ?\DateTime $start_from = null,

        #[WithCast('date')]
        public ?\DateTime $due_to = null,

        public int $page = 1,
        public int $per_page = 20,
    ) {}
}
