<?php

namespace App\Repositories\Interfaces;

interface IIssueRepository extends IBaseRepository
{
    public function findByProject(int $projectId);
}