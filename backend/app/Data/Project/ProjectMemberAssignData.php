<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

/**
 * @property int $user_id
 * @property string[] $roles
 */

class ProjectMemberAssignData extends Data
{
    public function __construct(
        public int $user_id,
        public array $roles,
    ) {}
}
