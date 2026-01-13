<?php

namespace App\Repositories\Implementations;

use App\Models\Issue;
use App\Repositories\Interfaces\IIssueRepository;

class IssueRepository extends BaseRepository implements IIssueRepository
{
    public function __construct(Issue $model)
    {
        parent::__construct($model);
    }

    public function findByProject(int $projectId)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->get();
    }
}
