<?php

namespace App\Data\Collections;

use App\Data\Issue\TaskCommentData;
use Spatie\LaravelData\DataCollection;

class TaskCommentDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(TaskCommentData::class, $items);
    }
}

