<?php

namespace App\Repository;

use App\Models\Milestone;
use App\Repository\Interfaces\MilestoneRepositoryInterface;

class MilestoneRepository extends BaseRepository implements MilestoneRepositoryInterface
{
    public function __construct(Milestone $milestone)
    {
        parent::__construct($milestone);
    }
}
