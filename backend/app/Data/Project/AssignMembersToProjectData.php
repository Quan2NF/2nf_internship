<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;
use App\Data\Project\ProjectMemberAssignData;

class AssignMembersToProjectData extends Data
{
    /**
     * @param ProjectMemberAssignData[] $members
     */
    public function __construct(
        public array $members,
    ) {}
}
