<?php

namespace App\Repository;

use App\Models\Issue;
use App\Repository\Interfaces\IssueRepositoryInterface;

class IssueRepository extends BaseRepository implements IssueRepositoryInterface
{
    public function __construct(Issue $issue)
    {
        parent::__construct($issue);
    }
}
