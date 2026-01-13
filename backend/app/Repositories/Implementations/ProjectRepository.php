<?php

namespace App\Repositories\Implementations;

use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;

class ProjectRepository extends BaseRepository implements IProjectRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function findByUser(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->get();
    }
}