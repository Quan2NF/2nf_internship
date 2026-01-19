<?php

namespace App\Data\Project;

use App\Models\Project;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public int $user_id,
        public ?string $status,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    /**
     * Create ProjectData from Project model.
     */
    public static function fromModel(Project $project): self
    {
        return new self(
            id: (int) $project->id,
            name: (string) $project->name,
            description: $project->description,
            user_id: (int) $project->user_id,
            status: $project->status,
            created_at: optional($project->created_at)?->toISOString(),
            updated_at: optional($project->updated_at)?->toISOString(),
        );
    }
}
