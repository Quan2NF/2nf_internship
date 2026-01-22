<?php

namespace App\Data\Project;

use App\Models\Project;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public ?string $description,
        public string $status,

        public ?string $planned_start_date,
        public ?string $planned_end_date,
        public ?string $start_date,
        public ?string $end_date,

        public int $progress_rate,
        public int $is_public,
        public int $is_active,

        public int $created_by,
        public ?int $updated_by,

        public string $created_at,
        public string $updated_at,
    ) {}

    
    public static function fromModel(Project $project): self
    {
        return new self(
            id: (int) $project->id,
            code: (string) $project->code,
            name: (string) $project->name,
            description: $project->description,
            status: (string) $project->status,

            planned_start_date: $project->planned_start_date ? $project->planned_start_date->toDateString() : null,
            planned_end_date: $project->planned_end_date ? $project->planned_end_date->toDateString() : null,
            start_date: $project->start_date ? $project->start_date->toDateString() : null,
            end_date: $project->end_date ? $project->end_date->toDateString() : null,

            progress_rate: (int) $project->progress_rate,
            is_public: (int) $project->is_public,
            is_active: (int) $project->is_active,

            created_by: (int) $project->created_by,
            updated_by: $project->updated_by ? (int) $project->updated_by : null,

            created_at: $project->created_at?->toISOString() ?? '',
            updated_at: $project->updated_at?->toISOString() ?? '',
        );
    }
}
