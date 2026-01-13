<?php

namespace App\Data\Collections;

use App\Data\Issue\TaskLogData;
use Spatie\LaravelData\DataCollection;

class TaskLogDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(TaskLogData::class, $items);
    }
}

