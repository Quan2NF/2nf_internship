<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

/**
 * Data used to update project settings such as visibility and activity state.
 */
class ProjectSettingData extends Data
{
    /**
     * @param int $project_id The ID of the project
     * @param bool|null $is_public Whether the project is public
     * @param bool|null $is_active Whether the project is active
     */
    public function __construct(
        public int $project_id,
        public ?bool $is_public = null,
        public ?bool $is_active = null,
    ) {}
}
