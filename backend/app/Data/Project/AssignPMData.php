<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

/**
 * Data required to assign a Project Manager (PM) to a project.
 */
class AssignPMData extends Data
{
    /**
     * @param int $pm_id The user ID of the project manager to assign
     */
    public function __construct(
        public int $pm_id,
    ) {}
}
