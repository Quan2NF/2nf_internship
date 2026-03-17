<?php

namespace App\Data\Project;

use App\Data\Common\EntityData;
use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\LaravelData\Transformers\EnumTransformer;

/**
 * Data Transfer Object representing a Project entity.
 *
 * Mirrors the `projects` table structure, including nullability, default values,
 * and types.
 */
class ProjectResponseData extends EntityData
{
    public function __construct(
        public int $id,
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
        
        public string $status_label,
        public int $progress_rate = 0,
        public bool $is_public = false,
        public bool $is_active = true,
        public int $created_by,
        public int $updated_by,

        /** @var Collection<int, ProjectMemberResponseData> */
        public Collection $projectMembers,

        /** @var Collection<int, App\Data\Task\TaskResponseData> */
        public Collection $tasks,

        public ?ProjectMemberResponseData $pm,
        public array $task_progress,
    ) {}
}
