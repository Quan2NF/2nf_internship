<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

class AssignMembersToProjectData extends Data
{
    /**
     * @param int[] $user_ids Array of user IDs
     */
    public function __construct(
        public array $user_ids,
    ) {}
}
