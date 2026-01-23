<?php

namespace App\Data\Project;

use App\Data\Common\EntityData;
use App\Enums\Project\ProjectStatus;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\EnumTransformer;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Data Transfer Object representing a Project entity.
 *
 * Mirrors the `projects` table structure, including nullability, default values,
 * and types.
 */
class ProjectRequestData extends EntityData
{
    /**
     * @param string $code Project code (unique, not null)
     * @param string $name Project name (not null)
     * @param string|null $description Project description (nullable)
     * @param ProjectStatus $status Project status enum (not null)
     * @param \DateTimeInterface|null $planned_start_date Planned start date (nullable)
     * @param \DateTimeInterface|null $planned_end_date Planned end date (nullable)
     * @param \DateTimeInterface|null $start_date Actual start date (nullable)
     * @param \DateTimeInterface|null $end_date Actual end date (nullable)
     * @param int $progress_rate Progress rate in percent (not null, default 0)
     * @param bool $is_public Whether the project is public (not null, default false)
     * @param bool $is_active Whether the project is active (not null, default true)
     */
    public function __construct(
        public string $code,
        public string $name,
        public ?string $description = null,
        #[WithTransformer(EnumTransformer::class)]
        public ProjectStatus $status,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $planned_start_date = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $planned_end_date = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $start_date = null,
        
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $end_date = null,
        
        public int $progress_rate = 0,
        public bool $is_public = false,
        public bool $is_active = true,
    ) {}
}
