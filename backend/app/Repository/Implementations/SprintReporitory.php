<?php

namespace App\Repository\Implementations;

use App\Models\Sprint;
use App\Repository\Interfaces\SprintRepositoryInterface;

class SprintReporitory extends BaseRepository implements SprintRepositoryInterface
{
    public function __construct(Sprint $sprint)
    {
        parent::__construct($sprint);
    }
}
