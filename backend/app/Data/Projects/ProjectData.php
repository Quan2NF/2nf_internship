<?php

namespace App\Data\Projects;

use App\Models\Project;
use App\Enums\ProjectStatus;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public ?string $description,
        public int $status,
        public string $statusLabel,
        public ?string $plannedStartDate,
        public ?string $plannedEndDate,
        public ?string $startDate,
        public ?string $endDate,
        public ?string $progressRate,
        public bool $isPublic,
        public bool $isActive,
    ) {}

    public static function fromModel(Project $project): self
    {
        // Get status value
        $statusValue = $project->status instanceof ProjectStatus
            ? $project->status->value
            : (int) $project->status;

        return new self(
            id: $project->id,
            code: $project->code,
            name: $project->name,
            description: $project->description,
            status: $statusValue,
            statusLabel: $project->status_label,
            plannedStartDate: $project->planned_start_date?->format('Y-m-d'),
            plannedEndDate: $project->planned_end_date?->format('Y-m-d'),
            startDate: $project->start_date?->format('Y-m-d'),
            endDate: $project->end_date?->format('Y-m-d'),
            progressRate: $project->progress_rate ? (string) floatval($project->progress_rate) : null,
            isPublic: (bool) $project->is_public,
            isActive: (bool) $project->is_active,
        );
    }

    /**
     * Transform collection of Project models to ProjectDataCollection
     */
    public static function fromModels($projects): \App\Data\Collections\ProjectDataCollection
    {
        $items = [];
        foreach ($projects as $project) {
            $items[] = self::fromModel($project);
        }
        return new \App\Data\Collections\ProjectDataCollection($items);
    }
}
