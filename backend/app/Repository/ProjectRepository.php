<?php

namespace App\Repository;

use App\Models\Project;
use App\Repository\Interfaces\ProjectRepositoryInterface;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }
}
