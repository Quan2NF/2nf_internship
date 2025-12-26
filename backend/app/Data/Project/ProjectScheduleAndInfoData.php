<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Data used to update a project's schedule and progress information.
 */
class ProjectScheduleAndInfoData extends Data
{
    /**
     * @param int $project_id The ID of the project
     * @param \DateTime|null $planned_start_date Planned start date
     * @param \DateTime|null $planned_end_date Planned end date
     * @param \DateTime|null $start_date Actual start date
     * @param \DateTime|null $end_date Actual end date
     * @param int|null $progress_rate Progress rate in percent
     */
    public function __construct(
        public int $project_id,

        #[WithCast('datetime')]
        public ?\DateTime $planned_start_date = null,

        #[WithCast('datetime')]
        public ?\DateTime $planned_end_date = null,

        #[WithCast('datetime')]
        public ?\DateTime $start_date = null,

        #[WithCast('datetime')]
        public ?\DateTime $end_date = null,

        public ?int $progress_rate = null,
    ) {}
}
