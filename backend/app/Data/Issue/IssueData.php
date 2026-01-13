<?php

namespace App\Data;

use App\Models\Issue;
use Spatie\LaravelData\Data;

class IssueData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public int $type,
        public string $typeLabel,
        public int $priority,
        public string $priorityLabel,
        public int $status,
        public string $statusLabel,
        public int $projectId,
        public ?int $assignedTo,
        public int $reportedBy,
        public ?float $estimatedHours,
        public ?float $actualHours,
        public ?string $dueDate,
        public ?string $resolution,
        public bool $isPublic,
        public bool $isActive,
    ) {}

    public static function fromModel(Issue $issue): self
    {
        return new self(
            id: $issue->id,
            title: $issue->title,
            description: $issue->description,
            type: $issue->type,
            typeLabel: $issue->type_label,
            priority: $issue->priority,
            priorityLabel: $issue->priority_label,
            status: $issue->status,
            statusLabel: $issue->status_label,
            projectId: $issue->project_id,
            assignedTo: $issue->assigned_to,
            reportedBy: $issue->reported_by,
            estimatedHours: $issue->estimated_hours ? floatval($issue->estimated_hours) : null,
            actualHours: $issue->actual_hours ? floatval($issue->actual_hours) : null,
            dueDate: $issue->due_date?->format('Y-m-d'),
            resolution: $issue->resolution,
            isPublic: (bool) $issue->is_public,
            isActive: (bool) $issue->is_active,
        );
    }

    /**
     * Transform collection of Issue models to IssueDataCollection
     */
    public static function fromModels($issues): \App\Data\Collections\IssueDataCollection
    {
        return new \App\Data\Collections\IssueDataCollection(
            $issues->map(fn(Issue $issue) => self::fromModel($issue))->toArray()
        );
    }
}
