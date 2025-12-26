<?php

namespace App\Data\Project;

use App\Data\Common\EntityData;
use App\Enums\Project\ProjectStatus;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Data Transfer Object representing a Project entity.
 *
 * Mirrors the `projects` table structure, including nullability, default values,
 * and types.
 */
class ProjectData extends EntityData
{
    /**
     * @param int $id Project ID (inherited from EntityData)
     * @param string $code Project code (unique, not null)
     * @param string $name Project name (not null)
     * @param string|null $description Project description (nullable)
     * @param ProjectStatus $status Project status enum (not null)
     * @param \DateTime|null $planned_start_date Planned start date (nullable)
     * @param \DateTime|null $planned_end_date Planned end date (nullable)
     * @param \DateTime|null $start_date Actual start date (nullable)
     * @param \DateTime|null $end_date Actual end date (nullable)
     * @param int $progress_rate Progress rate in percent (not null, default 0)
     * @param bool $is_public Whether the project is public (not null, default false)
     * @param bool $is_active Whether the project is active (not null, default true)
     * @param int $created_by User ID of the creator (not null)
     * @param int $updated_by User ID of the last updater (not null)
     */
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public ?string $description = null,
        #[WithCast('enum:App\Enums\Project\ProjectStatus')]
        public ProjectStatus $status,
        #[WithCast('datetime')]
        public ?\DateTime $planned_start_date = null,
        #[WithCast('datetime')]
        public ?\DateTime $planned_end_date = null,
        #[WithCast('datetime')]
        public ?\DateTime $start_date = null,
        #[WithCast('datetime')]
        public ?\DateTime $end_date = null,
        public int $progress_rate = 0,
        public bool $is_public = false,
        public bool $is_active = true,
        public int $created_by,
        public int $updated_by,
    ) {}
}
